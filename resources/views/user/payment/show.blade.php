@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="card p-4">
                    <h5 class="mb-3">Data Pemesan</h5>
                    <form>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" class="form-control" placeholder="Masukkan Nama">
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" placeholder="Masukkan Email">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Telepon</label>
                                <input type="text" class="form-control" placeholder="Masukkan No. Telepon">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card p-4 mb-3">
                    <h5 class="mb-3">Jumlah Tiket</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <img src="assets/images/Balekambang Beach.jpeg" class="rounded me-3 ticket-image"
                                alt="Tiket Roller Coaster">
                            <div>
                                <p class="mb-0">Tiket Roller Coaster</p>
                                <small>November 8, 2020</small>
                            </div>
                        </div>
                        <span>1 Tiket</span>
                    </div>
                </div>
                <div class="card p-4">
                    <h5 class="mb-3">Total Pembayaran</h5>
                    <div class="d-flex justify-content-between">
                        <p>1 Tiket Roller Coaster</p>
                        <p>IDR 999.000</p>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <p><strong>Total</strong></p>
                        <p><strong>IDR 999.000</strong></p>
                    </div>
                    <button class="btn btn-custom w-100 mt-3">Bayar</button>
                </div>
            </div>
        </div>
    </div>
@endsection
