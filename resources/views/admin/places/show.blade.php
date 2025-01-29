@extends('admin.layout.layout')

@php
    $title = 'Detail Tempat';
    $subTitle = 'Tempat - Detail';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <h4 class="fw-bold text-primary">{{ $place->name }}</h4>
                    <div class="row">
                        <!-- Nama Destinasi -->
                        <div class="col-sm-6">
                            <p><strong>Nama Tempat:</strong> {{ $place->name }}</p>
                        </div>

                        <!-- Tipe Destinasi -->
                        <div class="col-sm-6">
                            <p><strong>Tipe Destinasi:</strong> {{ ucfirst($place->type) }}</p>
                        </div>

                        <!-- Lokasi -->
                        <div class="col-sm-6">
                            <p><strong>Lokasi:</strong> {{ $place->location }}</p>
                        </div>

                        <!-- Jam Operasional -->
                        <div class="col-sm-6">
                            <p><strong>Jam Operasional:</strong> {{ $place->open_time }} -
                                {{ $place->close_time }}</p>
                        </div>

                        <!-- Wisata Terdekat -->
                        <div class="col-sm-6">
                            <p><strong>Wisata Terdekat:</strong> {{ $place->destination->name }}</p>
                        </div>

                        <!-- Harga Tiket -->
                        <div class="col-sm-6">
                            <p><strong>Harga Tiket:</strong> Rp {{ number_format($place->price, 0, ',', '.') }}</p>
                        </div>

                        <!-- Harga Tiket Akhir Pekan -->
                        <div class="col-sm-6">
                            <p><strong>Harga Akhir Pekan:</strong> Rp
                                {{ number_format($place->weekend_price, 0, ',', '.') }}</p>
                        </div>

                        <!-- Harga Tiket Anak-anak -->
                        <div class="col-sm-6">
                            <p><strong>Harga Anak-anak:</strong> Rp
                                {{ number_format($place->children_price, 0, ',', '.') }}</p>
                        </div>

                        <!-- Informasi Pembayaran -->
                        <div class="col-sm-6">
                            <p><strong>Rekening:</strong> {{ $place->account_number }}
                                ({{ $place->bank_name }})</p>
                            <p><strong>Atas Nama:</strong> {{ $place->account_name }}</p>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-sm-12" style="margin-top: 20px">
                            <p><strong>Deskripsi:</strong></p>
                            <p>{{ $place->description }}</p>
                        </div>

                        <!-- Galeri -->
                        <div class="col-sm-12">
                            <p><strong>Galeri:</strong></p>
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($place->gallery as $image)
                                    <img src="{{ asset('storage/' . $image) }}" alt="Gambar Wisata"
                                        class="rounded shadow-sm" width="150">
                                @endforeach
                            </div>
                        </div>

                        <!-- Tombol Aksi -->
                        <div class="col-sm-12 mt-3">
                            <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Kembali</a>
                            <a href="{{ route('admin.places.edit', $place->id) }}" class="btn btn-primary">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
