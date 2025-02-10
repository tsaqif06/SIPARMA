@extends('user.layouts.app')
@php
    $withoutNavbar = true;
    $script = '<script>
        $(".reveal6").click(function() {
            let pwdInput = $(".pwd6");
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
                    <form class="wpo-accountWrapper" action="{{ route('login.post') }}" method="post">
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
                                <h2>Login</h2>
                                <p>Masuk ke akun anda</p>
                            </div>

                            <div class="row">
                                <!-- Email -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <label class="text-secondary">Email</label>
                                    <input type="text" id="email" name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        placeholder="Masukkan Email Anda" value="{{ old('email') }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Password -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="form-group">
                                        <label class="text-secondary">Password</label>
                                        <div class="input-group">
                                            <input class="pwd6 form-control @error('password') is-invalid @enderror"
                                                type="password" placeholder="Masukkan Password Anda" name="password">
                                            <span class="input-group-btn">
                                                <button class="reveal6 btn btn-outline-secondary" type="button"
                                                    style="margin-top: -30px;">
                                                    <iconify-icon icon="iconoir:eye" width="24"
                                                        height="24"></iconify-icon>
                                                </button>
                                            </span>
                                        </div>
                                        @error('password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                {{--  <!-- Remember Me -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <div class="check-box-wrap">
                                        <div class="input-box">
                                            <input type="checkbox" id="remember" name="remember" value="1"
                                                {{ old('remember') ? 'checked' : '' }}>
                                            <label for="remember">Ingat Saya</label>
                                        </div>
                                    </div>
                                </div>  --}}

                                <!-- Tombol Login -->
                                <div class="col-lg-12 col-md-12 col-12">
                                    <button type="submit" class="wpo-accountBtn">Login</button>
                                </div>
                            </div>

                            <p class="subText pt-3">Belum mempunyai akun?
                                <a href="{{ route('register') }}">Silahkan buat akun di sini</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
