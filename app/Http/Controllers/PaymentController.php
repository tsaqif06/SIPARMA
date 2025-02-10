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
                'first_name' => auth()->user()->name,
                'email' => auth()->user()->email,
                'phone' => auth()->user()->phone_number,
            ],
        ];

        $snapToken = Snap::getSnapToken($params);

        return response()->json(['snapToken' => $snapToken]);
    }
}
