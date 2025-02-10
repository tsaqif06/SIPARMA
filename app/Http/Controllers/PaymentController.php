<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($transaction_code)
    {
        $transaction = Transaction::with('destination')
            ->where('transaction_code', $transaction_code)
            ->firstOrFail();

        return view('user.payment.show', compact('transaction'));
    }

    public function processPayment(Request $request, $transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);

        $transaction->status = 'paid';
        $transaction->save();

        return redirect()->route('payment.success', ['transaction' => $transaction_id]);
    }

    public function success($transaction_id)
    {
        $transaction = Transaction::findOrFail($transaction_id);
        return view('payment.success', compact('transaction'));
    }
}
