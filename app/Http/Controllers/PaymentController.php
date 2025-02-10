<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function show($transaction_code)
    {
        $transaction = Transaction::with('destination')
            ->where('transaction_code', $transaction_code)
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
                $transaction->status = 'paid';
            } elseif ($request->transaction_status == 'pending') {
                $transaction->status = 'pending';
            } elseif ($request->transaction_status == 'expire' || $request->transaction_status == 'cancel') {
                $transaction->status = 'failed';
            }

            $transaction->save();
        }

        return response()->json(['message' => 'Transaction status updated']);
    }

    public function invoice($order_id)
    {
        $transaction = Transaction::where('transaction_code', $order_id)
            ->where('status', 'paid')
            ->firstOrFail();

        return view('user.payment.invoice', compact('transaction'));
    }
}
