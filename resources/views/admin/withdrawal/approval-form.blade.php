@extends('admin.layout.layout')

@php
    $title = 'Penyetujuan Request Pencairan Saldo';
    $subTitle = 'Request Pencairan Saldo';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <form action="{{ route('admin.withdrawal.updateStatus', $withdrawal->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Nama Destinasi -->
                                    <input type="hidden" name="status" value="completed">

                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="admin_note"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Catatan
                                                Admin</label>
                                            <textarea name="admin_note" id="admin_note" class="form-control radius-8 @error('admin_note') is-invalid @enderror"
                                                rows="5"></textarea>
                                            @error('admin_note')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Deskripsi Fasilitas -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="transfer_proof"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Bukti
                                                Transfer</label>
                                            <input type="file" name="transfer_proof" id="transfer_proof"
                                                class="form-control radius-8 @error('transfer_proof') is-invalid @enderror"
                                                required>
                                            <small class="text-muted">Format: JPG, JPEG, PNG, PDF (Maksimal 2MB)</small>
                                            @error('transfer_proof')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="{{ route('admin.withdrawal.approval', $type) }}"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Setujui</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
