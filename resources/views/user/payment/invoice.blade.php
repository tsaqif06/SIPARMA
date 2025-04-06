@extends('user.layouts.app')

@php
    \Carbon\Carbon::setLocale(app()->getLocale());
@endphp

@section('content')
    <div class="invoice-container p-3 p-md-4 mx-5 mx-md-5">
        <div class="invoice-header mb-4 d-flex flex-column flex-md-row justify-content-between align-items-center">
            <h4 class="text-center text-md-start my-3 mb-md-0">{{ __('main.invoice_title') }}</h4>
            <img src="{{ asset('assets/user/images/LOGO_SIPARMA_.png') }}" alt="Logo" class="img-fluid"
                style="max-width: 150px;">
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-6">
                <p><strong>{{ __('main.payment_code') }}:</strong> {{ $transaction->transaction_code }}</p>
                <p><strong>{{ __('main.nama') }}:</strong> {{ $transaction->user->name }}</p>
            </div>
            <div class="col-12 col-md-6 text-md-end">
                <p><strong>{{ __('main.payment_time') }}:</strong>
                    {{ \Carbon\Carbon::parse($transaction->created_at)->translatedFormat('d F Y') }}
                </p>
                <p><strong>{{ __('main.visit_time') }}:</strong>
                    {{ \Carbon\Carbon::parse($transaction->tickets[0]->visit_date)->translatedFormat('d F Y') }}
                </p>
            </div>
        </div>

        @php
            $type_translation = [
                'destination' => __('main.tiket_wisata'),
                'ride' => __('main.tiket_wahana'),
                'bundle' => __('main.tiket_bundle'),
            ];
        @endphp

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>{{ __('main.nama_tiket') }}</th>
                        <th>{{ __('main.harga') }}</th>
                        <th>{{ __('main.quantity') }}</th>
                        <th>{{ __('main.sub_total') }}</th>
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
                                    $ticket->item->weekend_price -
                                    ($ticket->item->weekend_price * $discountedPrice) / 100;

                                $adultPrice = $isWeekend ? $hargaDiskonWeekend : $hargaDiskon;

                                $discountedPriceChildren = $ticket->item->promos[0]->discount ?? 0;
                                $hargaDiskonAnak =
                                    $ticket->item->children_price -
                                    ($ticket->item->children_price * $discountedPriceChildren) / 100;
                            @endphp

                            @if ($ticket->adult_count > 0)
                                <tr>
                                    <td>{{ $type_translation[$ticket->item_type] }} - {{ $ticket->item->getTranslatedName() }}
                                        ({{ __('main.dewasa') }})</td>
                                    <td>IDR {{ number_format($adultPrice, 0, ',', '.') }}</td>
                                    <td>{{ $ticket->adult_count }}</td>
                                    <td>IDR {{ number_format($ticket->adult_count * $adultPrice, 0, ',', '.') }}</td>
                                </tr>
                            @endif

                            @if ($ticket->children_count > 0)
                                <tr>
                                    <td>{{ $type_translation[$ticket->item_type] }} - {{ $ticket->item->getTranslatedName() }}
                                        ({{ __('main.anakanak') }})</td>
                                    <td>IDR {{ number_format($hargaDiskonAnak, 0, ',', '.') }}</td>
                                    <td>{{ $ticket->children_count }}</td>
                                    <td>IDR {{ number_format($ticket->children_count * $hargaDiskonAnak, 0, ',', '.') }}
                                    </td>
                                </tr>
                            @endif
                        @else
                            <tr>
                                <td>
                                    {{ $type_translation[$ticket->item_type] }} - {{ $ticket->item->getTranslatedName() }}
                                    <ul class="mb-3 ms-4">
                                        @foreach ($ticket->item->items as $it)
                                            @php
                                                $quantities = collect(json_decode($it->quantity, true))
                                                    ->map(function ($qty, $key) {
                                                        $label =
                                                            $key === 'adults' ? __('main.dewasa') : __('main.anakanak');
                                                        return $label . ': ' . $qty;
                                                    })
                                                    ->implode(', ');
                                            @endphp
                                            <li class="text-muted">{{ __('main.tiket') }} {{ optional($it->item)->name }}
                                                <small class="text-muted">({{ $quantities }})</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>IDR {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                <td>1</td>
                                <td>IDR {{ number_format(1 * $transaction->amount, 0, ',', '.') }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>{{ __('main.biaya_admin') }}:</strong></td>
                        <td><strong>IDR {{ number_format(config('app.admin_fee'), 0, ',', '.') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="3" class="text-end"><strong>{{ __('main.total') }}:</strong></td>
                        <td><strong>IDR {{ number_format($transaction->total_pay, 0, ',', '.') }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
@endsection
