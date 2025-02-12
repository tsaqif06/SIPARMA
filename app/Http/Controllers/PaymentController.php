<?php

namespace App\Http\Controllers;

use Midtrans\Snap;
use Midtrans\Config;
use App\Models\Balance;
use App\Models\BalanceLog;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    public function show($transaction_code)
    {
        $transaction = Transaction::with('destination')
            ->where('transaction_code', $transaction_code)
            ->where('status', 'pending')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $type_translation = [
            'destination' => 'Tiket Wisata',
            'ride' => 'Tiket Wahana',
            'bundle' => 'Tiket Paket'
        ];

        foreach ($transaction->tickets as $ticket) {
            $ticket->translated_type = $type_translation[$ticket->item_type] ?? 'Tiket';
        }

        return view('user.payment.show', compact('transaction'));
    }

    public function process(Request $request)
    {
        $transaction = Transaction::findOrFail($request->transaction_id);

        Config::$serverKey = config('services.midtrans.server_key');
        Config::$isProduction = config('services.midtrans.is_production');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => $transaction->amount,
            ],
            'customer_details' => [
                'first_name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone_number,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snapToken' => $snapToken]);
    }

    public function callback(Request $request)
    {
        $transaction = Transaction::where('transaction_code', $request->order_id)->first();

        if ($transaction) {
            if ($request->transaction_status == 'settlement' || $request->transaction_status == 'capture') {
                Balance::updateOrCreate(
                    ['destination_id' => $transaction->destination_id],
                    [
                        'balance' => DB::raw('balance + ' . $transaction->amount),
                        'total_profit' => DB::raw('total_profit + ' . $transaction->amount)
                    ]
                );

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

                $transaction->status = 'paid';
                $transaction->save();
            } elseif ($request->transaction_status == 'pending') {
                $transaction->status = 'pending';
                $transaction->save();
            } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                $transaction->status = 'failed';
                $transaction->save();
            }
        }

        return response()->json(['message' => 'Transaction status updated']);
    }

    public function invoice($order_id)
    {
        $transaction = Transaction::where('transaction_code', $order_id)
            ->where('status', 'paid')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        return view('user.payment.invoice', compact('transaction'));
    }

    public function downloadInvoice($order_id)
    {
        $transaction = Transaction::where('transaction_code', $order_id)
            ->where('status', 'paid')
            ->where('user_id', auth()->user()->id)
            ->firstOrFail();

        $pdf = Pdf::loadView('user.payment.invoice-pdf', compact('transaction'))->setPaper('A4', 'portrait');
        return $pdf->download("Invoice_{$transaction->transaction_code}.pdf");
    }
}
