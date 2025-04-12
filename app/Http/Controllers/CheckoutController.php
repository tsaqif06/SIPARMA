<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionTicket;
use Illuminate\Support\Str;

/**
 * Controller untuk mengelola proses checkout pengguna, termasuk menyimpan transaksi dan tiket.
 * Fungsionalitas lainnya termasuk validasi input, penyimpanan data transaksi, dan pengalihan
 * pengguna ke halaman pembayaran berdasarkan transaksi yang dibuat.
 */
class CheckoutController extends Controller
{
    /**
     * Menyimpan transaksi checkout dan tiket terkait.
     *
     * Fungsi ini menerima data checkout yang valid, melakukan validasi,
     * menyimpan transaksi dan tiket terkait ke dalam database,
     * dan mengarahkan pengguna ke halaman pembayaran dengan kode transaksi.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'destination_id' => 'required|exists:tbl_destinations,id',
            'item_id' => 'required',
            'item_type' => 'required',
            'visit_date' => 'required|date|after_or_equal:today',
            'adult_count' => $request->item_type === 'bundle' ? 'nullable' : 'required|integer|min:0',
            'children_count' => $request->item_type === 'bundle' ? 'nullable' : 'required|integer|min:0',
            'total_price' => 'required|numeric|min:1',
        ]);

        // Simpan transaksi
        $transaction = Transaction::create([
            'user_id' => auth()->user()->id,
            'destination_id' => $request->destination_id,
            'amount' => $request->total_price,
            'status' => 'pending',
            'transaction_code' => 'TX-' . Str::upper(Str::random(10))
        ]);

        // Simpan tiket transaksi
        TransactionTicket::create([
            'transaction_id' => $transaction->id,
            'item_type' => $request->item_type,
            'item_id' => $request->item_id,
            'adult_count' => $request->adult_count ?? null,
            'children_count' => $request->children_count ?? null,
            'subtotal' => $request->total_price,
            'visit_date' => $request->visit_date,
        ]);

        return redirect()->route('payment.show', ['transaction' => $transaction->transaction_code]);
    }
}
