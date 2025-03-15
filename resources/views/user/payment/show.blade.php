@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card p-4">
                    <h5 class="mb-3">Data Pemesan</h5>
                    <form id="checkout-form">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" value="{{ old('name', auth()->user()->name) }}"
                                placeholder="Masukkan Nama" id="name" name="name">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="{{ old('email', auth()->user()->email) }}"
                                    placeholder="Masukkan Email" id="email" name="email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control"
                                    value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                    placeholder="Masukkan No. Telepon" id="phone_number" name="phone_number">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card p-4 mb-3">
                    <h5 class="mb-3">Jumlah Tiket</h5>
                    @foreach ($transaction->tickets as $ticket)
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset(
                                    $ticket->item_type === 'bundle'
                                        ? $ticket->item->items[0]->item->gallery[0]->image_url ?? 'assets/images/default.png'
                                        : $ticket->item->gallery[0]->image_url ?? 'assets/images/default.png',
                                ) }}"
                                    class="rounded me-3 ticket-image" loading="lazy" alt="Tiket">

                                <div>
                                    <p class="mb-0">{{ $ticket->translated_type }} - {{ $ticket->item->name }}</p>
                                    <small>{{ \Carbon\Carbon::parse($ticket->visit_date)->format('F d, Y') }}</small>
                                </div>
                            </div>
                            @if ($ticket->item_type !== 'bundle')
                                <span>{{ $ticket->adult_count + $ticket->children_count }} Tiket</span>
                            @else
                                <span>1 Tiket</span>
                            @endif
                        </div>
                    @endforeach
                </div>

                @php
                    $discountedPrice = $ticket->item->promos[0]->discount ?? 0;
                    $hargaDiskon = $ticket->item->price - ($ticket->item->price * $discountedPrice) / 100;
                    $hargaDiskonWeekend =
                        $ticket->item->weekend_price - ($ticket->item->weekend_price * $discountedPrice) / 100;

                    $discountedPriceChildren = $ticket->item->promos[0]->discount ?? 0;
                    $hargaDiskonAnak =
                        $ticket->item->children_price -
                        ($ticket->item->children_price * $discountedPriceChildren) / 100;
                @endphp

                <div class="card p-4">
                    <h5 class="mb-3">Total Pembayaran</h5>
                    @foreach ($transaction->tickets as $ticket)
                        @if ($ticket->item_type !== 'bundle')
                            @if ($ticket->adult_count > 0)
                                <div class="d-flex justify-content-between">
                                    <p>{{ $ticket->translated_type }} - {{ $ticket->item->name }}
                                        ({{ $ticket->adult_count }} Dewasa)
                                    </p>
                                    <p>IDR
                                        {{ number_format(
                                            (\Carbon\Carbon::parse($ticket->visit_date)->isWeekend() ? $hargaDiskonWeekend : $hargaDiskon) *
                                                $ticket->adult_count,
                                            0,
                                            ',',
                                            '.',
                                        ) }}
                                    </p>
                                </div>
                            @endif

                            @if ($ticket->children_count > 0)
                                <div class="d-flex justify-content-between">
                                    <p>{{ $ticket->translated_type }} - {{ $ticket->item->name }}
                                        ({{ $ticket->children_count }} Anak)
                                    </p>
                                    <p>IDR
                                        {{ number_format($hargaDiskonAnak * $ticket->children_count, 0, ',', '.') }}
                                    </p>
                                </div>
                            @endif
                        @else
                            @php
                                $type_translation = [
                                    'destination' => 'Tiket Wisata',
                                    'ride' => 'Tiket Wahana',
                                    'bundle' => 'Tiket Bundle',
                                ];
                            @endphp
                            <div class="d-flex justify-content-between">
                                <div class="d-flex flex-column">
                                    <p class="mb-0">{{ $type_translation[$ticket->item_type] }} -
                                        {{ $ticket->item->name }}</p>
                                    <ul class="list-unstyled mb-3">
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
                                </div>
                                <div>
                                    <p>IDR
                                        {{ number_format($transaction->amount, 0, ',', '.') }}
                                    </p>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex justify-content-between">
                            <p>Biaya Admin</p>
                            <p>IDR
                                {{ number_format(config('app.admin_fee'), 0, ',', '.') }}
                            </p>
                        </div>
                    @endforeach
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p><strong>Total</strong></p>
                        <p><strong>IDR {{ number_format($transaction->total_pay, 0, ',', '.') }}</strong></p>
                    </div>
                    <button class="btn btn-custom w-100 mt-3" id="pay-button">Bayar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
