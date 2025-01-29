@extends('admin.layout.layout')
@php
    $title = 'Tambah Tempat';
    $subTitle = 'Tempat - Tambah';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <!-- Upload Image Start -->
                            <form action="{{ route('admin.places.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Nama Destinasi -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                Tempat<span class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="Masukkan Nama Tempat"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tipe Destinasi -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="type"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe
                                                Tempat<span class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('type') is-invalid @enderror"
                                                id="type" name="type" placeholder="Cth: Restoran, Penginapan, dll."
                                                value="{{ old('type') }}" required>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="location"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Lokasi<span
                                                    class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('location') is-invalid @enderror"
                                                id="location" name="location" placeholder="Masukkan Lokasi"
                                                value="{{ old('location') }}" required>
                                            @error('location')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="destination_id"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Wisata
                                                Terdekat<span class="text-danger-600">*</span></label>
                                            <select
                                                class="form-control radius-8 form-select @error('destination_id') is-invalid @enderror"
                                                id="destination_id" name="destination_id" required>
                                                <option value="">Pilih Wisata Terdekat</option>
                                                @foreach ($destinations as $destination)
                                                    <option value="{{ $destination->id }}"
                                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                                        {{ $destination->id }} - {{ $destination->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @error('destination_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{--  <!-- Jam Buka -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="open_time"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jam Buka</label>
                                            <input type="time"
                                                class="form-control radius-8 @error('open_time') is-invalid @enderror"
                                                id="open_time" name="open_time" value="{{ old('open_time') }}">
                                            @error('open_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Jam Tutup -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="close_time"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jam
                                                Tutup</label>
                                            <input type="time"
                                                class="form-control radius-8 @error('close_time') is-invalid @enderror"
                                                id="close_time" name="close_time" value="{{ old('close_time') }}">
                                            @error('close_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Harga
                                                Tiket</label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('price') is-invalid @enderror"
                                                id="price" name="price" placeholder="Masukkan Harga Tiket"
                                                value="{{ old('price') }}">
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket Akhir Pekan -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="weekend_price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Harga Tiket
                                                Akhir Pekan</label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('weekend_price') is-invalid @enderror"
                                                id="weekend_price" name="weekend_price"
                                                placeholder="Masukkan Harga Tiket Akhir Pekan"
                                                value="{{ old('weekend_price') }}">
                                            @error('weekend_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket Anak-anak -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="children_price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Harga Tiket
                                                Anak-anak</label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('children_price') is-invalid @enderror"
                                                id="children_price" name="children_price"
                                                placeholder="Masukkan Harga Tiket Anak-anak"
                                                value="{{ old('children_price') }}">
                                            @error('children_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nomor Rekening -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="account_number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                                Rekening</label>
                                            <input type="text"
                                                class="form-control radius-8 @error('account_number') is-invalid @enderror"
                                                id="account_number" name="account_number"
                                                placeholder="Masukkan Nomor Rekening" value="{{ old('account_number') }}"
                                                >
                                            @error('account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nama Bank -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="bank_name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                Bank</label>
                                            <input type="text"
                                                class="form-control radius-8 @error('bank_name') is-invalid @enderror"
                                                id="bank_name" name="bank_name" placeholder="Masukkan Nama Bank"
                                                value="{{ old('bank_name') }}">
                                            @error('bank_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Nama Pemilik Rekening -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="account_name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama Pemilik
                                                Rekening</label>
                                            <input type="text"
                                                class="form-control radius-8 @error('account_name') is-invalid @enderror"
                                                id="account_name" name="account_name"
                                                placeholder="Masukkan Nama Pemilik Rekening"
                                                value="{{ old('account_name') }}">
                                            @error('account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="description"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi</label>
                                            <textarea class="form-control radius-8 @error('description') is-invalid @enderror" id="description"
                                                name="description" rows="4" placeholder="Masukkan Deskripsi Destinasi">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Upload Gambar Galeri -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="gallery_images"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Gambar
                                                Galeri</label>
                                            <input type="file"
                                                class="form-control radius-8 @error('gallery_images') is-invalid @enderror"
                                                id="gallery_images" name="gallery_images[]" multiple accept="image/*">
                                            @error('gallery_images')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>  --}}
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
