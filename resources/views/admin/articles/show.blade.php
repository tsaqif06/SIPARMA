@extends('admin.layout.layout')

@php
    $title = 'Lihat Keluhan';
    $subTitle = 'Keluhan - Lihat';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="user_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Nama Pelapor
                                </label>
                                <input type="text" class="form-control radius-8" id="user_id" name="user_id"
                                    value="{{ $complaint->user->name }}" readonly>
                            </div>
                        </div>

                        @if ($complaint->destination)
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="destination_id"
                                        class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Nama Wisata
                                    </label>
                                    <input type="text" class="form-control radius-8" id="destination_id"
                                        name="destination_id" value="{{ $complaint->destination->name }}" readonly>
                                </div>
                            </div>
                        @else
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="place_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Nama Tempat
                                    </label>
                                    <input type="text" class="form-control radius-8" id="place_id" name="place_id"
                                        value="{{ $complaint->place->name }}" readonly>
                                </div>
                            </div>
                        @endif

                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="created_at" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Waktu Dibuat
                                </label>
                                <input type="text" class="form-control radius-8" id="created_at" name="created_at"
                                    value="{{ $complaint->created_at->format('d-m-Y') }}" readonly>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="status" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Status Keluhan
                                </label>
                                <input type="text" class="form-control radius-8" id="status" name="status"
                                    value="{{ $complaint->status == 'new' ? 'Baru' : ($complaint->status == 'resolved' ? 'Selesai' : 'Ditutup') }}"
                                    readonly>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="mb-20">
                                <label for="complaint_text" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Isi Keluhan
                                </label>
                                <textarea class="form-control radius-8" id="complaint_text" name="complaint_text" readonly>{{ $complaint->complaint_text }}</textarea>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a href="{{ route('admin.complaints.index') }}"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                            <a href="{{ route('admin.complaints.edit', $complaint->id) }}">
                                <button type="button"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Ubah
                                    Status</button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
