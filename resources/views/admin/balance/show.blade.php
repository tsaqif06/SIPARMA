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
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                        Destinasi</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $balance->destination->name }}" readonly>
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
                                        Profit Keseluruhan</label>
                                    <input type="text" class="form-control radius-8"
                                        value="Rp {{ number_format($balance->total_profit, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
