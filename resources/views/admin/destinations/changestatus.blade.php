@extends('admin.layout.layout')

@php
    $title = 'Ubah Status Wisata';
    $subTitle = 'Wisata - Ubah Status';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <form action="{{ route('admin.recommendations.updateStatus', $recommendation->id) }}"
                                method="POST">
                                @csrf
                                @method('PUT')
                                <div class="row">
                                    <!-- Status -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="status"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Status<span
                                                    class="text-danger-600">*</span></label>

                                            <select class="form-select radius-8 @error('status') is-invalid @enderror"
                                                id="status" name="status" required>
                                                <option value="pending" @if ($recommendation->status == 'pending') selected @endif>
                                                    Menunggu</option>
                                                <option value="approved" @if ($recommendation->status == 'approved') selected @endif>
                                                    Disetujui</option>
                                                <option value="rejected" @if ($recommendation->status == 'rejected') selected @endif>
                                                    Ditolak</option>
                                            </select>
                                            @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Kembali -->
                                <div class="d-flex align-items-center justify-content-center gap-3 mt-3">
                                    <a href="{{ route('admin.destinations.recommendation') }}"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                                    <button type="submit" class="btn btn-primary text-md px-56 py-12 radius-8">Ubah
                                        Status</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
