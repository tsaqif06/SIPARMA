@extends('admin.layout.layout')

@php
    $title = 'Tambah Item Bundle';
    $subTitle = 'Item Bundle - Tambah';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <!-- Form Tambah Bundle -->
                    <form action="{{ route('admin.bundle.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Nama Bundle -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Nama Bundle<span class="text-danger-600">*</span>
                                    </label>
                                    <input type="text" class="form-control radius-8 @error('name') is-invalid @enderror"
                                        id="name" name="name" placeholder="Masukkan Nama Bundle"
                                        value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Diskon (Opsional) -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="discount" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Diskon
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-base"> % </span>
                                        <input type="number" step="0.01"
                                            class="form-control radius-8 @error('discount') is-invalid @enderror"
                                            id="discount" name="discount" placeholder="Masukkan Diskon (Opsional)"
                                            value="{{ old('discount') }}">
                                        @error('discount')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Deskripsi -->
                            <div class="col-sm-12">
                                <div class="mb-20">
                                    <label for="description" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Deskripsi
                                    </label>
                                    <textarea class="form-control radius-8 @error('description') is-invalid @enderror" id="description" name="description"
                                        rows="4" placeholder="Masukkan Deskripsi Bundle (Opsional)">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                    <!-- End Form -->
                </div>
            </div>
        </div>
    </div>
@endsection
