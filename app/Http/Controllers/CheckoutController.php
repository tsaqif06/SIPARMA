<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use App\Models\TransactionTicket;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'destination_id' => 'required|exists:tbl_destinations,id',
            'visit_date' => 'required|date|after_or_equal:today',
            'adult_count' => 'required|integer|min:0',
            'children_count' => 'required|integer|min:0',
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
            'item_type' => 'destination',
            'item_id' => $request->destination_id,
            'adult_count' => $request->adult_count,
            'children_count' => $request->children_count,
            'subtotal' => $request->total_price,
            'visit_date' => $request->visit_date,
        ]);

        return redirect()->route('payment.show', ['transaction' => $transaction->id]);
    }
}
