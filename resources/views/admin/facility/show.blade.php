@extends('admin.layout.layout')

@php
    $title = 'Lihat Wisata';
    $subTitle = 'Wisata - Lihat';
@endphp

@section('content')
    <div class="container">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base" style="padding: 20px 30px">
            <!-- Header Destinasi -->
            <div class="destination-header mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold">{{ $destination->name }}</h1>
                        <p>{{ $destination->location }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        @php $bg = $destination->status == 'Buka' ? 'success' : 'danger'; @endphp
                        <span
                            class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">{{ $destination->status }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Gambar Utama -->
            @if ($destination->gallery->count() > 0)
                <div class="destination-images mb-5">
                    <div id="carouselExample" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach ($destination->gallery as $index => $image)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <img src="{{ asset($image->image_url) }}" class="d-block w-100" alt="Gallery Image"
                                        style="height: 400px; object-fit: cover;">
                                </div>
                            @endforeach
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselExample"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>
            @endif

            <!-- Informasi Utama -->
            <div class="destination-info mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="fw-bold">Deskripsi</h3>
                        <p>{{ $destination->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Detail</h3>
                        <ul class="list-unstyled">
                            <li><strong>Tipe:</strong> {{ ucfirst($destination->type) }}</li>
                            <li><strong>Jam Operasional:</strong> {{ $destination->open_time }} -
                                {{ $destination->close_time }}
                            </li>
                            <li><strong>Harga Tiket:</strong> Rp {{ number_format($destination->price, 0, ',', '.') }}</li>
                            <li><strong>Harga Akhir Pekan:</strong> Rp
                                {{ number_format($destination->weekend_price, 0, ',', '.') }}</li>
                            <li><strong>Harga Anak-anak:</strong> Rp
                                {{ number_format($destination->children_price, 0, ',', '.') }}</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Galeri Destinasi -->
            @if ($destination->gallery->count() > 0)
                <div class="destination-gallery mb-5">
                    <h3 class="fw-bold">Galeri</h3>
                    <div class="row">
                        @foreach ($destination->gallery as $image)
                            <div class="col-md-3 mb-3">
                                <img src="{{ asset($image->image_url) }}" class="img-fluid rounded" alt="Gallery Image"
                                    style="height: 150px; object-fit: cover;">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Informasi Pembayaran -->
            <div class="destination-payment mb-5">
                <h3 class="fw-bold">Informasi Pembayaran</h3>
                <ul class="list-unstyled">
                    <li><strong>Nomor Rekening:</strong> {{ $destination->account_number }}</li>
                    <li><strong>Nama Bank:</strong> {{ $destination->bank_name }}</li>
                    <li><strong>Nama Pemilik Rekening:</strong> {{ $destination->account_name }}</li>
                </ul>
            </div>

            <!-- Tombol Aksi -->
            <div class="destination-actions">
                @if (auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.destinations.index') }}" class="btn btn-secondary">Kembali</a>
                @endif
                <a href="{{ route('admin.destinations.edit', $destination->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
