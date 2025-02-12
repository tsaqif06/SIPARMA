@extends('user.layouts.app')

@php
    \Carbon\Carbon::setLocale('id');
@endphp

@section('content')
    <div class="invoice-container p-3 p-md-4 mx-5 mx-md-5">
        <div class="invoice-header mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h4 class="text-center text-md-start my-3 mb-md-0">INVOICE | PEMBAYARAN BERHASIL</h4>
            <img src="{{ asset('assets/user/images/LOGO_SIPARMA_.png') }}" alt="Logo" class="img-fluid"
                style="max-width: 150px;">
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-6">
                <p><strong>Kode Pembayaran:</strong> {{ $transaction->transaction_code }}</p>
                <p><strong>Nama:</strong> {{ $transaction->user->name }}</p>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <p><strong>Waktu Pembayaran:</strong>
                    {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
                </p>
                <p><strong>Waktu Kunjungan:</strong>
                    {{ \Carbon\Carbon::parse($transaction->tickets[0]->visit_date)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Jumlah</th>
                        <th>Nama Tiket</th>
                        <th>Harga</th>
                        <th>Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->tickets as $ticket)
                        @php
                            $isWeekend = \Carbon\Carbon::parse($ticket->visit_date)->isWeekend();
                            $adultPrice = $isWeekend ? $ticket->item->weekend_price : $ticket->item->price;
                        @endphp

                        @if ($ticket->adult_count > 0)
                            <tr>
                                <td>{{ $ticket->adult_count }}</td>
                                <td>{{ $ticket->item->name }} (Dewasa)</td>
                                <td>IDR {{ number_format($adultPrice, 0, ',', '.') }}</td>
                                <td>IDR {{ number_format($ticket->adult_count * $adultPrice, 0, ',', '.') }}</td>
                            </tr>
                        @endif

                        @if ($ticket->children_count > 0)
                            <tr>
                                <td>{{ $ticket->children_count }}</td>
                                <td>{{ $ticket->item->name }} (Anak-anak)</td>
                                <td>IDR {{ number_format($ticket->item->children_price, 0, ',', '.') }}</td>
                                <td>IDR
                                    {{ number_format($ticket->children_count * $ticket->item->children_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td><strong>IDR {{ number_format($transaction->amount, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
