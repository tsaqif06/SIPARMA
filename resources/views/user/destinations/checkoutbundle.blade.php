@extends('user.layouts.app')

@php
    $script = '<script>
        $(document).ready(function() {
            // ** FORM HANDLING FIX **
            $("#checkout-form").submit(function(event) {
                // Ambil data dari UI sebelum submit
                let visitDate = $("#visit-date").val();
                let totalPrice = $("#total-price").text().replace(/\D/g, ""); // Hapus format ribuan

                // Validasi: Pastikan tanggal sudah dipilih
                if (!visitDate) {
                    alert(' . json_encode(__("main.silahkan_pilih_tanggal")) . ');
                    event.preventDefault(); // Stop submit jika tidak valid
                    return;
                }

                // **Set nilai input hidden sebelum submit**
                $("#visit-date-input").val(visitDate);
                $("#total-price-input").val(totalPrice);
            });

            // Inisialisasi harga awal
            updatePrice();
        });
    </script>
';
@endphp

@section('content')
    <div class="container">
        <!-- Title: Beli Tiket -->
        <div class="text-center mb-4">
            <h2>{{ __('main.beli_tiket') }} {{ $item->getTranslatedName() }}</h2>
        </div>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-7">
                    <h4>{{ __('main.pilih_tanggal') }}</h4>
                    <hr>
                    <input type="date" id="visit-date" name="visit_date"
                        class="form-control w-100 mt-4 @error('visit_date') is-invalid @enderror" required
                        min="{{ date('Y-m-d') }}" value="{{ old('visit_date') }}">

                    <!-- Tampilkan error message jika validasi gagal -->
                    @error('visit_date')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror

                    <h4 style="margin-top: 40px">{{ __('main.pilih_tiket') }}</h4>
                    <hr style="margin-bottom: 20px">
                    <div class="pricing-area">
                        <div class="ticket-card">
                            <div class="img lazy-bg"
                                data-bg="{{ !empty($item->gallery) && isset($item->gallery[0]) ? '../../../' . $item->gallery[0]->image_url : asset('assets/images/default.png') }}">
                            </div>
                            <div class="ticket-info">
                                <div class="ticket-title">{{ __('main.tiket_bundle') }} - {{ $item->getTranslatedName() }}
                                </div>
                                <div class="ticket-desc">{{ __('main.isi_item_bundle') }}:
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($item->items as $it)
                                            @php
                                                $quantities = collect(json_decode($it->quantity, true))
                                                    ->map(function ($qty, $key) {
                                                        $label =
                                                            $key === 'adults' ? __('main.dewasa') : __('main.anakanak');
                                                        return $label . ': ' . $qty;
                                                    })
                                                    ->implode(', ');
                                            @endphp
                                            <li>{{ __('main.tiket') }} {{ optional($it->item)->getTranslatedName() }}
                                                <small class="text-muted">({{ $quantities }})</small>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>

                            @php
                                $discountedPrice = $item->discount ?? 0;
                                $hargaDiskon = $item->total_price - ($item->total_price * $discountedPrice) / 100;
                            @endphp

                            <div class="ticket-price-button">
                                @if ($discountedPrice > 0)
                                    <span class="price-old">IDR
                                        {{ number_format($item->total_price, 0, ',', '.') }}</span>
                                @endif
                                <span class="ticket-price">IDR
                                    {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="card p-3">
                        <h5 class="mb-3">{{ __('main.tujuan_wisata') }}</h5>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <img src="{{ asset($item->gallery[0]->image_url ?? 'assets/images/default.png') }}"
                                alt="{{ __('main.tujuan_wisata') }}" class="img-fluid mb-3"
                                style="width: 100%; max-width: 200px; height: auto; object-fit: cover; border-radius: 10px;">
                            <div class="text-center">
                                <p class="h6">{{ __('main.harga_total') }}</p>
                                <p class="h4 text-danger">IDR <span
                                        id="total-price">{{ number_format($hargaDiskon, 0, ',', '.') }}</span></p>
                                @error('total_price')
                                    <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                                <form id="checkout-form" action="{{ route('checkout.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="destination_id"
                                        value="{{ $item->destination->id ?? $item->id }}">
                                    <input type="hidden" name="item_id" value="{{ $item->id }}">
                                    <input type="hidden" name="item_type" value="{{ $type }}">
                                    <input type="hidden" id="visit-date-input" name="visit_date">
                                    <input type="hidden" id="total-price-input" name="total_price">

                                    <button type="submit"
                                        class="btn btn-transparent float-end">{{ __('main.pesan_sekarang') }}</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Syarat & Ketentuan -->
        <div class="card p-3 mt-4">
            <div class="row">
                <div class="col-12">
                    <h3>{{ __('main.syarat_ketentuan') }}</h3>
                    <p><strong>{{ __('main.informasi_umum') }}</strong></p>
                    <ul class="ms-4">
                        <li>{{ __('main.pastikan_informasi_benar') }}</li>
                        <li>{{ __('main.tiket_tidak_dapat_dikembalikan') }}</li>
                        <li>{{ __('main.perubahan_sesuai_kebijakan') }}</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
