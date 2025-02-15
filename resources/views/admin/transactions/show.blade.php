@extends('admin.layout.layout')

@php
    $title = 'Lihat Detail Transaksi';
    $subTitle = 'Transaksi - Lihat';
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3>Detail Transaksi</h3>
                    </div>
                    <div class="card-body">
                        <!-- Informasi Transaksi -->
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama User</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $transaction->user->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                        Wisata</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $transaction->destination->name }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah
                                        Total</label>
                                    <input type="text" class="form-control radius-8"
                                        value="Rp {{ number_format($transaction->amount, 0, ',', '.') }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Status</label>
                                    @php
                                        $status = match ($transaction->status) {
                                            'pending' => 'Menunggu',
                                            'paid' => 'Lunas',
                                            'failed' => 'Gagal',
                                            default => 'Tidak Diketahui',
                                        };
                                    @endphp
                                    <input type="text" class="form-control radius-8" value="{{ ucfirst($status) }}"
                                        readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Kode
                                        Transaksi</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $transaction->transaction_code }}" readonly>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal
                                        Transaksi</label>
                                    <input type="text" class="form-control radius-8"
                                        value="{{ $transaction->created_at }}" readonly>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Tiket Transaksi -->
                        <h4 class="mt-40">Detail Tiket</h4>
                        <div class="row">
                            @foreach ($transaction->tickets as $ticket)
                                <div class="col-md-12 mb-20">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe
                                                            Item</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="{{ $ticket->item_type == 'destination' ? 'Wisata' : 'Wahana' }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tiket</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="Tiket {{ $ticket->item->name }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah
                                                            Dewasa</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="{{ $ticket->adult_count }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah
                                                            Anak</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="{{ $ticket->children_count }}" readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Subtotal</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="Rp {{ number_format($ticket->subtotal, 0, ',', '.') }}"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="mb-20">
                                                        <label
                                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Tanggal
                                                            Kunjungan</label>
                                                        <input type="text" class="form-control radius-8"
                                                            value="{{ $ticket->visit_date }}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
