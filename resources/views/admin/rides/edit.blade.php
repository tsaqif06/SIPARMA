@extends('admin.layout.layout')
@php
    $title = 'Edit Wahana';
    $subTitle = 'Wahana - Edit';
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
                            <form action="{{ route('admin.rides.update', $ride->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Nama Destinasi -->
                                    <input type="hidden" name="destination_id"
                                        value="{{ auth()->user()->adminDestinations[0]->destination_id }}">
                                    <!-- Nama Wahana -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Nama Wahana<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="text"
                                                class="form-control radius-8 @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="Masukkan Nama Wahana"
                                                value="{{ old('name', $ride->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Jam Buka -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="open_time"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Jam Buka<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="time"
                                                class="form-control radius-8 @error('open_time') is-invalid @enderror"
                                                id="open_time" name="open_time"
                                                value="{{ old('open_time', $ride->open_time) }}" required>
                                            @error('open_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Jam Tutup -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="close_time"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Jam Tutup<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="time"
                                                class="form-control radius-8 @error('close_time') is-invalid @enderror"
                                                id="close_time" name="close_time"
                                                value="{{ old('close_time', $ride->close_time) }}" required>
                                            @error('close_time')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Harga Tiket<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('price') is-invalid @enderror"
                                                id="price" name="price" placeholder="Masukkan Harga Tiket"
                                                value="{{ old('price', $ride->price) }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket Akhir Pekan -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="weekend_price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Harga Tiket Akhir Pekan<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('weekend_price') is-invalid @enderror"
                                                id="weekend_price" name="weekend_price"
                                                placeholder="Masukkan Harga Tiket Akhir Pekan"
                                                value="{{ old('weekend_price', $ride->weekend_price) }}" required>
                                            @error('weekend_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Harga Tiket Anak-anak -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="children_price"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Harga Tiket Anak-anak<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="number" step="0.01"
                                                class="form-control radius-8 @error('children_price') is-invalid @enderror"
                                                id="children_price" name="children_price"
                                                placeholder="Masukkan Harga Tiket Anak-anak"
                                                value="{{ old('children_price', $ride->children_price) }}" required>
                                            @error('children_price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Batasan Usia Minimal -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="min_age"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Batasan Usia Minimal
                                            </label>
                                            <input type="number"
                                                class="form-control radius-8 @error('min_age') is-invalid @enderror"
                                                id="min_age" name="min_age" placeholder="Masukkan Usia Minimal"
                                                value="{{ old('min_age', $ride->min_age) }}">
                                            @error('min_age')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Batasan Tinggi Minimal -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="min_height"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Batasan Tinggi Minimal
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-base"> cm </span>
                                                <input type="number"
                                                    class="form-control radius-8 @error('min_height') is-invalid @enderror"
                                                    id="min_height" name="min_height"
                                                    placeholder="Masukkan Tinggi Minimal"
                                                    value="{{ old('min_height', $ride->min_height) }}">
                                                @error('min_height')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="operational_status"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Status
                                                Operasional</label>
                                            <select
                                                class="form-control radius-8 @error('operational_status') is-invalid @enderror"
                                                id="operational_status" name="operational_status">
                                                <option value="open"
                                                    {{ old('operational_status', $ride->operational_status) == 'open' ? 'selected' : '' }}>
                                                    Buka</option>
                                                <option value="closed"
                                                    {{ old('operational_status', $ride->operational_status) == 'closed' ? 'selected' : '' }}>
                                                    Tutup</option>
                                            </select>
                                            @error('operational_status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Deskripsi -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="description"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Deskripsi
                                            </label>
                                            <textarea class="form-control radius-8 @error('description') is-invalid @enderror" id="description"
                                                name="description" rows="4" placeholder="Masukkan Deskripsi Wahana">{{ old('description', $ride->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="{{ route('admin.rides.index') }}"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
