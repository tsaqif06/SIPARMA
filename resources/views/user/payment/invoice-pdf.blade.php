<!DOCTYPE html>
<html lang="en">

@php
    \Carbon\Carbon::setLocale('id');
@endphp

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <style>
        /* General Reset */
        body {
            margin: 0;
            padding: 0;
            font-family: "Sora", Arial, sans-serif;
            color: #000000;
        }

        /* Invoice Container */
        .invoice-container {
            background-color: #ffffff;
            border: 1px solid #9db2bf;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: 0 auto;
        }

        /* Invoice Header */
        .invoice-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .invoice-header img {
            height: 150px;
            /* Larger logo size for invoice */
        }

        .invoice-header h4 {
            margin: 0;
            font-size: 1.5rem;
            font-weight: bold;
        }

        /* Invoice Details */
        .invoice-details {
            margin-bottom: 20px;
        }

        .invoice-details p {
            margin: 5px 0;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table th,
        table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #9db2bf;
        }

        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        table tfoot td {
            border-top: 2px solid #9db2bf;
            font-weight: bold;
        }

        /* Utility Classes */
        .text-center {
            text-align: center;
        }

        .text-end {
            text-align: right;
        }

        .mb-4 {
            margin-bottom: 1.5rem;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="invoice-header mb-4">
            <h4 style="margin-top: 20px">INVOICE | PEMBAYARAN BERHASIL</h4>
            <img src="{{ public_path('assets/user/images/LOGO_SIPARMA_.png') }}" alt="Logo" class="img-fluid">
        </div>

        <div class="invoice-details">
            <p><strong>Kode Pembayaran:</strong> {{ $transaction->transaction_code }}</p>
            <p><strong>Nama:</strong> {{ $transaction->user->name }}</p>
            <p><strong>Waktu Pembayaran:</strong>
                {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
            </p>
            <p><strong>Waktu Kunjungan:</strong>
                {{ \Carbon\Carbon::parse($transaction->tickets[0]->visit_date)->translatedFormat('d F Y') }}
            </p>
        </div>

        @php
            $type_translation = [
                'destination' => 'Tiket Wisata',
                'ride' => 'Tiket Wahana',
                'bundle' => 'Tiket Bundle',
            ];
        @endphp

        <table>
            <thead>
                <tr>
                    <th>Nama Tiket</th>
                    <th>Harga</th>
                    <th>Jumlah</th>
                    <th>Sub Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($transaction->tickets as $ticket)
                    @if ($ticket->item_type !== 'bundle')
                        @php
                            $isWeekend = \Carbon\Carbon::parse($ticket->visit_date)->isWeekend();

                            $discountedPrice = $ticket->item->promos[0]->discount ?? 0;
                            $hargaDiskon = $ticket->item->price - ($ticket->item->price * $discountedPrice) / 100;
                            $hargaDiskonWeekend =
                                $ticket->item->weekend_price - ($ticket->item->weekend_price * $discountedPrice) / 100;

                            $adultPrice = $isWeekend ? $hargaDiskonWeekend : $hargaDiskon;

                            $discountedPriceChildren = $ticket->item->promos[0]->discount ?? 0;
                            $hargaDiskonAnak =
                                $ticket->item->children_price -
                                ($ticket->item->children_price * $discountedPriceChildren) / 100;
                        @endphp

                        @if ($ticket->adult_count > 0)
                            <tr>
                                <td>{{ $ticket->item->name }} (Dewasa)</td>
                                <td>IDR {{ number_format($adultPrice, 0, ',', '.') }}</td>
                                <td>{{ $ticket->adult_count }}</td>
                                <td>IDR {{ number_format($ticket->adult_count * $adultPrice, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        @if ($ticket->children_count > 0)
                            <tr>
                                <td>{{ $ticket->item->name }} (Anak-anak)</td>
                                <td>IDR {{ number_format($hargaDiskonAnak, 0, ',', '.') }}</td>
                                <td>{{ $ticket->children_count }}</td>
                                <td>IDR
                                    {{ number_format($ticket->children_count * $hargaDiskonAnak, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    @else
                        <tr>
                            <td>
                                {{ $type_translation[$ticket->item_type] }} - {{ $ticket->item->name }}
                                <ul class="mb-3 ms-4">
                                    @foreach ($ticket->item->items as $it)
                                        @php
                                            $quantities = collect(json_decode($it->quantity, true))
                                                ->map(function ($qty, $key) {
                                                    $label = $key === 'adults' ? 'Dewasa' : 'Anak-anak';
                                                    return $label . ': ' . $qty;
                                                })
                                                ->implode(', ');
                                        @endphp
                                        <li class="text-muted">Tiket {{ optional($it->item)->name }}
                                            <small class="text-muted">({{ $quantities }})</small>
                                        </li>
                                    @endforeach
                                </ul>
                            </td>
                            <td>IDR {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                            <td>1</td>
                            <td>IDR
                                {{ number_format(1 * $transaction->amount, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" class="text-end"><strong>Biaya Admin:</strong></td>
                    <td><strong>IDR {{ number_format(config('app.admin_fee'), 0, ',', '.') }}</strong></td>
                </tr>
                <tr>
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>IDR {{ number_format($transaction->total_pay, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>
    </div>
</body>

</html>
