@extends('admin.layout.layout')
@php
    $title = 'Tambah Fasilitas';
    $subTitle = 'Fasilitas - Tambah';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <!-- Form Start -->
                            <form action="{{ route('admin.facility.store', $type) }}" method="POST">
                                @csrf
                                <div class="row">
                                    <input type="hidden" name="item_type" value="{{ $type }}">
                                    <input type="hidden" name="item_id" value="{{ $destinationId }}">
                                    <!-- Nama Fasilitas -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                Fasilitas<span class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('name') is-invalid @enderror"
                                                id="name" name="name"
                                                placeholder="Masukkan Nama Fasilitas. Cth: Wifi, Toilet, Musholla"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Deskripsi Fasilitas -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="description"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi</label>
                                            <textarea class="form-control radius-8 @error('description') is-invalid @enderror" id="description" name="description"
                                                rows="4" placeholder="Masukkan Deskripsi Fasilitas (optional)">{{ old('description') }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
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
