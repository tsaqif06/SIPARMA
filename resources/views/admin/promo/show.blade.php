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
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Diskon
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text bg-base"> % </span>
                                    <input type="text" class="form-control radius-8" id="discount" name="discount"
                                        value="{{ number_format($promo->discount, 0) }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="valid_from" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Tanggal Mulai
                                </label>
                                <input type="date" class="form-control radius-8" id="valid_from" name="valid_from"
                                    value="{{ $promo->valid_from }}" readonly>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="valid_until" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Tanggal Berakhir
                                </label>
                                <input type="date" class="form-control radius-8" id="valid_until" name="valid_until"
                                    value="{{ $promo->valid_until }}" readonly>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a href="{{ route('admin.promo.index') }}"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                            <a href="{{ route('admin.promo.edit', $promo->id) }}">
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
