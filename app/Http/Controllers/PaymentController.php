<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Balance;
use App\Models\BalanceLog;
use App\Models\Transaction;
use App\Models\AdminBalance;
use Illuminate\Http\Request;
use App\Models\AdminBalanceLog;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk menangani logika pembayaran pengguna.
 * Termasuk pemrosesan pembayaran, status transaksi, dan pembuatan invoice.
 */
class PaymentController extends Controller
{
    /**
     * Biaya admin yang akan ditambahkan ke setiap transaksi.
     *
     * @var float
     */
    private $adminFee;

    /**
     * Constructor untuk mengatur biaya admin.
     * Menyimpan nilai biaya admin dari konfigurasi aplikasi.
     */
    public function __construct()
    {
        $this->adminFee = config('app.admin_fee');
    }

    /**
     * Menampilkan halaman pembayaran berdasarkan kode transaksi.
     *
     * Mencari transaksi yang sedang pending berdasarkan kode transaksi
     * dan menampilkan detail pembayaran serta tiket yang dibeli oleh pengguna.
     *
     * @param  string  $transaction_code
     * @return \Illuminate\View\View
     */
    public function show($transaction_code)
    {
        $transaction = Transaction::with('destination')
            ->where('transaction_code', $transaction_code)
            ->where('status', 'pending')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        // Terjemahan untuk jenis tiket
        $type_translation = [
            'destination' => __('main.tiket_wisata'),
            'ride' => __('main.tiket_wahana'),
            'bundle' => __('main.tiket_bundle')
        ];

        // Menambahkan biaya admin ke total pembayaran
        $transaction->total_pay = $transaction->amount + $this->adminFee;

        // Menambahkan jenis tiket yang sudah diterjemahkan
        foreach ($transaction->tickets as $ticket) {
            $ticket->translated_type = $type_translation[$ticket->item_type] ?? 'Tiket';
        }

        return view('user.payment.show', compact('transaction'));
    }

    /**
     * Memproses transaksi dan membuat token Snap Midtrans untuk pembayaran.
     *
     * Membuat token untuk transaksi yang diajukan dan mengirimkannya
     * ke Midtrans untuk pemrosesan pembayaran.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function process(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        // Menyiapkan parameter untuk transaksi Midtrans
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->amount + $this->adminFee,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone_number,
            ],
        ];

        // Mengambil token Snap Midtrans
        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snapToken' => $snapToken]);
    }

    /**
     * Callback untuk menerima status pembayaran dari Midtrans.
     *
     * Memproses status transaksi yang diterima dari Midtrans
     * dan memperbarui status transaksi pada sistem.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(Request $request)
    {
        $transaction = Transaction::where('transaction_code', $request->order_id)->first();

        if ($transaction) {
            if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                $adminFee = $this->adminFee;

                // Update saldo untuk destinasi dan admin
                Balance::updateOrCreate(
                    ['destination_id' => $transaction->destination_id],
                    [
                        'balance' => DB::raw('balance + ' . $transaction->amount),
                        'total_profit' => DB::raw('total_profit + ' . $transaction->amount)
                    ]
                );

                AdminBalance::updateOrCreate(
                    ['id' => 1],
                    [
                        'balance' => DB::raw('balance + ' . $adminFee),
                    ]
                );

                // Log transaksi untuk destinasi dan admin
                BalanceLog::updateOrCreate(
                    [
                        'destination_id' => $transaction->destination_id,
                        'period_year' => now()->year,
                        'period_month' => now()->month,
                    ],
                    [
                        'profit' => DB::raw('profit + ' . $transaction->amount),
                    ]
                );

                AdminBalanceLog::updateOrCreate(
                    [
                        'period_year' => now()->year,
                        'period_month' => now()->month,
                    ],
                    [
                        'profit' => DB::raw('profit + ' . $adminFee),
                    ]
                );

                // Update status transaksi menjadi 'paid'
                $transaction->status = 'paid';
                $transaction->save();
            } elseif ($request->transaction_status == 'pending') {
                // Jika status transaksi masih pending
                $transaction->status = 'pending';
                $transaction->save();
            } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                // Jika transaksi dibatalkan atau kedaluwarsa
                $transaction->status = 'failed';
                $transaction->save();
            }
        }

        return response()->json(['message' => 'Transaction status updated']);
    }

    /**
     * Menampilkan halaman invoice untuk transaksi yang sudah dibayar.
     *
     * Menampilkan invoice transaksi yang telah berhasil dibayar.
     *
     * @param  string  $order_id
     * @return \Illuminate\View\View
     */
    public function invoice($order_id)
    {
        $transaction = Transaction::with([
            'user:id,name',
            'tickets',
        ])
            ->where('transaction_code', $order_id)
            ->where('status', 'paid')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $transaction->total_pay = $transaction->amount + $this->adminFee;

        return view('user.payment.invoice', compact('transaction'));
    }

    /**
     * Mengunduh invoice sebagai file PDF untuk transaksi yang sudah dibayar.
     *
     * Membuat dan mengunduh file PDF yang berisi detail transaksi.
     *
     * @param  string  $order_id
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\Response
     */
    public function downloadInvoice($order_id)
    {
        $transaction = Transaction::where('transaction_code', $order_id)
            ->where('status', 'paid')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $transaction->total_pay = $transaction->amount + $this->adminFee;

        $pdf = Pdf::loadView('user.payment.invoice-pdf', compact('transaction'))->setPaper('A4', 'portrait');
        return $pdf->download("Invoice_{$transaction->transaction_code}.pdf");
    }
}
