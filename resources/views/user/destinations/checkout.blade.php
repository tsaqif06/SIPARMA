@extends('user.layouts.app')

@php
    $script = '<script>
        $(document).ready(function() {
            function updatePrice() {
                let selectedDate = new Date($("#visit-date").val());
                let day = selectedDate.getDay(); // 0 = Minggu, 6 = Sabtu

                if (isNaN(day)) return; // Jika belum pilih tanggal, jangan lakukan apa-apa

                let isWeekend = (day === 0 || day === 6);
                let newAdultPrice = isWeekend ? $("#adult-price").data("weekend") : $("#adult-price").data(
                    "weekday");

                $("#adult-price").text(parseFloat(newAdultPrice).toLocaleString("id-ID"));
                $("#child-price").text(parseFloat($("#child-price").data("price")).toLocaleString("id-ID"));

                updateTotal();
            }

            function updateQuantity(type, change) {
                let quantityElement = $(`#${type}-quantity`);
                let quantity = parseInt(quantityElement.text()) + change;
                quantity = Math.max(0, quantity); // Pastikan tidak negatif
                quantityElement.text(quantity);
                updateTotal();
            }

            function updateTotal() {
                let adultQuantity = parseInt($("#adult-quantity").text());
                let childQuantity = parseInt($("#child-quantity").text());

                let adultPrice = parseInt($("#adult-price").text().replace(/\D/g, ""));
                let childPrice = parseInt($("#child-price").text().replace(/\D/g, ""));

                let total = (adultQuantity * adultPrice) + (childQuantity * childPrice);
                $("#total-price").text(total.toLocaleString("id-ID"));
            }

            // Saat tanggal dipilih
            $("#visit-date").change(updatePrice);

            // Saat tombol + / - ditekan
            $(document).on("click", ".qty-btn", function() {
                let type = $(this).data("type");
                let change = parseInt($(this).data("change"));
                updateQuantity(type, change);
            });

            // ** FORM HANDLING FIX **
            $("#checkout-form").submit(function(event) {
                // Ambil data dari UI sebelum submit
                let visitDate = $("#visit-date").val();
                let adultCount = $("#adult-quantity").text();
                let childrenCount = $("#child-quantity").text();
                let totalPrice = $("#total-price").text().replace(/\D/g, ""); // Hapus format ribuan

                // Validasi: Pastikan tanggal sudah dipilih
                if (!visitDate) {
                    alert("Silakan pilih tanggal kunjungan.");
                    event.preventDefault(); // Stop submit jika tidak valid
                    return;
                }

                // **Set nilai input hidden sebelum submit**
                $("#visit-date-input").val(visitDate);
                $("#adult-count-input").val(adultCount);
                $("#children-count-input").val(childrenCount);
                $("#total-price-input").val(totalPrice);

                // **Debugging untuk cek apakah input hidden sudah terisi**
                console.log("Visit Date:", visitDate);
                console.log("Adult Count:", adultCount);
                console.log("Children Count:", childrenCount);
                console.log("Total Price:", totalPrice);
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
            <h2>Beli Tiket {{ $item->name }}</h2>
        </div>

        <!-- Date Picker -->
        <div class="card p-3 mb-4">
            <div class="row">
                <div class="col-12">
                    <h4>Pilih Tanggal</h4>
                    <input type="date" id="visit-date" name="visit_date"
                        class="form-control w-50 mx-auto @error('visit_date') is-invalid @enderror" required
                        min="{{ date('Y-m-d') }}" value="{{ old('visit_date') }}">

                    <!-- Tampilkan error message jika validasi gagal -->
                    @error('visit_date')
                        <div class="text-danger mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="container mt-5">
            <div class="row">
                <div class="col-md-7">
                    <div class="ticket-card-checkout">
                        <h5>Tiket Dewasa</h5>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($item->gallery[0]->image_url ?? 'assets/images/default.png') }}"
                                alt="Ticket Image" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <p class="text-muted">{{ $item->name }}</p>
                                <p class="text-danger">IDR <span id="adult-price" data-weekday="{{ $item->price }}"
                                        data-weekend="{{ $item->weekend_price }}">
                                        {{ number_format($item->price, 0, ',', '.') }}</span>
                                </p>
                                <div class="quantity-controls">
                                    <button class="qty-btn" data-type="adult" data-change="-1">-</button>
                                    <span id="adult-quantity" class="mx-3 text-secondary">0</span>
                                    <button class="qty-btn" data-type="adult" data-change="1">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ticket-card-checkout">
                        <h5>Tiket Anak-Anak</h5>
                        <div class="d-flex align-items-center">
                            <img src="{{ asset($item->gallery[0]->image_url ?? 'assets/images/default.png') }}"
                                alt="Ticket Image" class="me-3" style="width: 50px; height: 50px; object-fit: cover;">
                            <div>
                                <p class="text-muted">{{ $item->name }}</p>
                                <p class="text-danger">IDR <span id="child-price" data-price="{{ $item->children_price }}">
                                        {{ number_format($item->children_price, 0, ',', '.') }}</span>
                                </p>
                                <div class="quantity-controls">
                                    <button class="qty-btn" data-type="child" data-change="-1">-</button>
                                    <span id="child-quantity" class="mx-3 text-secondary">0</span>
                                    <button class="qty-btn" data-type="child" data-change="1">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="card p-3">
                        <h5 class="mb-3">Tujuan Wisata</h5>
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <img src="{{ asset($item->gallery[0]->image_url ?? 'assets/images/default.png') }}"
                                alt="Tujuan Wisata" class="img-fluid mb-3"
                                style="width: 100%; max-width: 200px; height: auto; object-fit: cover; border-radius: 10px;">
                            <div class="text-center">
                                <p class="h6">Harga Total</p>
                                <p class="h4 text-danger">IDR <span id="total-price">0</span></p>
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
                                    <input type="hidden" id="adult-count-input" name="adult_count">
                                    <input type="hidden" id="children-count-input" name="children_count">
                                    <input type="hidden" id="total-price-input" name="total_price">

                                    <button type="submit" class="btn btn-transparent float-end">Pesan Sekarang</button>
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
                    <h3>Syarat & Ketentuan</h3>
                    <p><strong>Informasi Umum</strong></p>
                    <ul class="ms-4">
                        <li>Pastikan informasi yang diisi sesuai.</li>
                        <li>Tiket tidak dapat dikembalikan atau dibatalkan.</li>
                        <li>Segala bentuk perubahan harus disesuaikan dengan kebijakan operator.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection
