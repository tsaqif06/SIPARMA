@extends('admin.layout.layout')

@php
    $title = 'Lihat Tempat';
    $subTitle = 'Tempat - Lihat';
@endphp

@section('content')
    <div class="container">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base" style="padding: 20px 30px">

            <!-- Header Tempat -->
            <div class="place-header mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold">{{ $place->name }}</h1>
                        <p>{{ $place->location }}</p>
                    </div>
                    <div class="col-md-4 text-end">
                        @php $bg = $place->operational_status == 'open' ? 'success' : 'danger'; @endphp
                        <span
                            class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">
                            {{ ucfirst($place->operational_status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Utama -->
            <div class="place-info mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="fw-bold">Deskripsi</h3>
                        <p>{{ $place->description }}</p>
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Detail</h3>
                        <ul class="list-unstyled">
                            <li><strong>Tipe:</strong> {{ ucfirst($place->type) }}</li>
                            <li><strong>Jam Operasional:</strong> {{ $place->open_time }} - {{ $place->close_time }}</li>
                            <li><strong>Harga Tiket:</strong> Rp {{ number_format($place->price, 0, ',', '.') }}</li>
                            <li><strong>Destinasi Terdekat:</strong>
                                {{ $place->destination ? $place->destination->name : '-' }}
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="place-actions">
                @if (auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Kembali</a>
                @endif
                <a href="{{ route('admin.places.edit', $place->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
