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
                            <form action="{{ route('admin.promo.update', $promo->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <input type="hidden" name="destination_id"
                                        value="{{ auth()->user()->adminDestinations[0]->destination_id ?? null }}">
                                    <input type="hidden" name="place_id"
                                        value="{{ auth()->user()->adminPlaces[0]->place_id ?? null }}">

                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="discount"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Diskon<span class="text-danger-600">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text bg-base"> % </span>
                                                <input type="number"
                                                    class="form-control radius-8 @error('discount') is-invalid @enderror"
                                                    id="discount" name="discount" placeholder="Masukkan Persentase Diskon"
                                                    value="{{ old('discount', number_format($promo->discount, 0)) }}">
                                                @error('discount')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Tanggal Mulai Berlaku -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="valid_from"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Tanggal Mulai<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control radius-8 @error('valid_from') is-invalid @enderror"
                                                id="valid_from" name="valid_from"
                                                value="{{ old('valid_from', $promo->valid_from) }}" required>
                                            @error('valid_from')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tanggal Berakhir -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="valid_until"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Tanggal Berakhir<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="date"
                                                class="form-control radius-8 @error('valid_until') is-invalid @enderror"
                                                id="valid_until" name="valid_until"
                                                value="{{ old('valid_until', $promo->valid_until) }}" required>
                                            @error('valid_until')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <a href="{{ route('admin.promo.index') }}"
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
