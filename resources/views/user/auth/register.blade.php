@extends('user.layouts.app')
@php
    $withoutNavbar = true;
    $withoutFooter = true;
    $script = '<script>
        $(".reveal6").click(function() {
            let pwdInput = $(this).closest(".form-group").find(".pwd6");
            let icon = $(this).find("iconify-icon");

            if (pwdInput.attr("type") === "password") {
                pwdInput.attr("type", "text");
                icon.attr("icon", "iconoir:eye-closed");
            } else {
                pwdInput.attr("type", "password");
                icon.attr("icon", "iconoir:eye");
            }
        });
    </script>';
@endphp

@section('content')
    <div class="wpo-login-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <form class="wpo-accountWrapper" action="{{ route('register.post') }}" method="post">
                        @csrf
                        <div class="wpo-accountInfo">
                            <div class="image">
                                <img src="{{ asset('assets/user/images/LOGO_SIPARMA_.png') }}" alt="">
                            </div>
                            <div class="back-home">
                                <a class="wpo-accountBtn" href="#" onclick="window.history.go(-1); return false;"
                                    <span class="">Kembali</span>
                                </a>
                            </div>
                        </div>
                        <div class="wpo-accountForm form-style">
                            <div class="fromTitle">
                                <h2>Daftar</h2>
                                <p>Daftarkan akun anda</p>
                            </div>

                            <div class="row">
                                <!-- Nama -->
                                <div class="col-lg-6 col-md-6 col-6">
                                    <label class="text-secondary">Nama</label>
                                    <input type="text" id="name" name="name"
                                        class="form-control @error('name') is-invalid @enderror" placeholder="Masukkan Nama"
                                        value="{{ old('name') }}">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="col-lg-6 col-md-6 col-6">
                                    <label class="text-secondary">Email</label>
                                    <input type="text" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Masukkan Email" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- No. Telepon -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <label class="text-secondary">No. Telepon</label>
                                    <input type="text" id="phone_number" name="phone_number"
                                        class="form-control @error('phone_number') is-invalid @enderror"
                                        placeholder="Masukkan Nomor Telepon" value="{{ old('phone_number') }}">
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label class="text-secondary">Password</label>
                                        <div class="input-group">
                                            <input class="pwd6 form-control @error('password') is-invalid @enderror"
                                                type="password" placeholder="Masukkan Password" name="password">
                                            <span class="input-group-btn">
                                                <button class="reveal6 btn btn-outline-secondary" type="button"
                                                    style="margin-top: -30px;">
                                                    <iconify-icon icon="iconoir:eye" width="24"
                                                        height="24"></iconify-icon>
                                                </button>
                                            </span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Konfirmasi Password -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label class="text-secondary">Konfirmasi Password</label>
                                        <div class="input-group">
                                            <input class="pwd6 form-control @error('password') is-invalid @enderror"
                                                type="password" placeholder="Konfirmasi Password"
                                                name="password_confirmation">
                                            <span class="input-group-btn">
                                                <button class="reveal6 btn btn-outline-secondary" type="button"
                                                    style="margin-top: -30px;">
                                                    <iconify-icon icon="iconoir:eye" width="24"
                                                        height="24"></iconify-icon>
                                                </button>
                                            </span>
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Daftar -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <button type="submit" class="wpo-accountBtn">Daftar</button>
                                </div>
                            </div>

                            <p class="subText pt-3">Sudah mempunyai akun?
                                <a href="{{ route('login') }}">Silahkan masuk di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
