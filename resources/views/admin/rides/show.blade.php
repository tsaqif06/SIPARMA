@extends('admin.layout.layout')

@php
    $title = 'Lihat Wahana';
    $subTitle = 'Wahana - Lihat';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="row">
                        <!-- Nama Wahana -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Nama Wahana
                                </label>
                                <input type="text" class="form-control radius-8" id="name" name="name"
                                    placeholder="Masukkan Nama Wahana" value="{{ $ride->name }}" readonly>
                            </div>
                        </div>

                        <!-- Jam Buka -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="open_time" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Jam Buka
                                </label>
                                <input type="time" class="form-control radius-8" id="open_time" name="open_time"
                                    value="{{ $ride->open_time }}" readonly>
                            </div>
                        </div>

                        <!-- Jam Tutup -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="close_time" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Jam Tutup
                                </label>
                                <input type="time" class="form-control radius-8" id="close_time" name="close_time"
                                    value="{{ $ride->close_time }}" readonly>
                            </div>
                        </div>

                        <!-- Harga Tiket -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="price" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Harga Tiket
                                </label>
                                <input type="number" step="0.01" class="form-control radius-8" id="price"
                                    name="price" placeholder="Masukkan Harga Tiket" value="{{ $ride->price }}" readonly>
                            </div>
                        </div>

                        <!-- Harga Tiket Akhir Pekan -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="weekend_price" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Harga Tiket Akhir Pekan
                                </label>
                                <input type="number" step="0.01" class="form-control radius-8" id="weekend_price"
                                    name="weekend_price" placeholder="Masukkan Harga Tiket Akhir Pekan"
                                    value="{{ $ride->weekend_price }}" readonly>
                            </div>
                        </div>

                        <!-- Harga Tiket Anak-anak -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="children_price" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Harga Tiket Anak-anak
                                </label>
                                <input type="number" step="0.01" class="form-control radius-8" id="children_price"
                                    name="children_price" placeholder="Masukkan Harga Tiket Anak-anak"
                                    value="{{ $ride->children_price }}" readonly>
                            </div>
                        </div>

                        <!-- Batasan Usia Minimal -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="min_age" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Batasan Usia Minimal
                                </label>
                                <input type="number" class="form-control radius-8" id="min_age" name="min_age"
                                    placeholder="Masukkan Usia Minimal" value="{{ $ride->min_age }}" readonly>
                            </div>
                        </div>

                        <!-- Batasan Tinggi Minimal -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="min_height" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Batasan Tinggi Minimal
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-base"> cm </span>
                                    <input type="number" class="form-control radius-8" id="min_height" name="min_height"
                                        placeholder="Masukkan Tinggi Minimal" value="{{ $ride->min_height }}" readonly>
                                </div>

                            </div>
                        </div>

                        <!-- Deskripsi -->
                        <div class="col-sm-12">
                            <div class="mb-20">
                                <label for="description" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Deskripsi
                                </label>
                                <textarea class="form-control radius-8" id="description" name="description" rows="4"
                                    placeholder="Masukkan Deskripsi Wahana" readonly>{{ $ride->description }}</textarea>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a href="{{ route('admin.rides.index') }}"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                            <a href="{{ route('admin.rides.edit', $ride->id) }}">
                                <button type="button"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Edit</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
