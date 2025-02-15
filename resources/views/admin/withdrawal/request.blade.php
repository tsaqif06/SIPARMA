@extends('admin.layout.layout')

@php
    $title = 'Ajukan Pencairan Saldo';
    $subTitle = 'Ajukan Pencairan Saldo';

    $script = '<script>
        function updateBalance(initialBalance) {
            let amount = document.getElementById("amount").value;
            let currentBalance = initialBalance - amount;
            document.getElementById("current_balance").value = currentBalance.toLocaleString("id-ID", {
                minimumFractionDigits: 2
            });
        }
    </script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <form action="{{ route('admin.withdrawal.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="current_balance"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Saldo
                                                Tersedia</label>
                                            <input type="text" id="current_balance" class="form-control radius-8"
                                                value="Rp {{ number_format($balance->balance, 0, ',', '.') }}" readonly>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="amount"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Jumlah Uang
                                                Yang Ingin Dicairkan</label>
                                            <input type="number" name="amount" id="amount"
                                                class="form-control radius-8 @error('amount') is-invalid @enderror"
                                                oninput="updateBalance({{ $balance->balance }})">
                                            @error('amount')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="{{ route('admin.withdrawal.approval') }}"
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
