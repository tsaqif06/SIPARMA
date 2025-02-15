@extends('admin.layout.layout')

@php
    $title = 'Lihat Detail Saldo Keuangan';
    $subTitle = 'Saldo Keuangan - Lihat';
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Detail Saldo</h3>
                    </div>
                    <div class="card-body">
                        <!-- Informasi Balance -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">ID Saldo</label>
                                    <input type="text" class="form-control radius-8" value="{{ $balance->id }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">ID
                                        Destinasi</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $balance->destination_id }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Saldo
                                        Terkini</label>
                                    <input type="text" class="form-control radius-8"
                                        value="Rp {{ number_format($balance->balance, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Total
                                        Profit</label>
                                    <input type="text" class="form-control radius-8"
                                        value="Rp {{ number_format($balance->total_profit, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Dibuat
                                        Pada</label>
                                    <input type="text" class="form-control radius-8" value="{{ $balance->created_at }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Diperbarui
                                        Pada</label>
                                    <input type="text" class="form-control radius-8" value="{{ $balance->updated_at }}"
                                        readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Informasi Destinasi (Opsional) -->
                        <h4 class="mt-40">Informasi Destinasi</h4>
                        <div class="row">
                            <div class="col-md-12 mb-20">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label
                                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                        Destinasi</label>
                                                    <input type="text" class="form-control radius-8"
                                                        value="{{ $balance->destination->name }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-20">
                                                    <label
                                                        class="form-label fw-semibold text-primary-light text-sm mb-8">Lokasi
                                                        Destinasi</label>
                                                    <input type="text" class="form-control radius-8"
                                                        value="{{ $balance->destination->location }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
