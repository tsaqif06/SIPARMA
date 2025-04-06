@extends('user.layouts.app')

@php
    $script = '<script>
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function() {
                const preview = document.getElementById("profile-preview");
                preview.src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>';
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-title text-secondary">{{ __('main.menu') }}</div>
                    <a href="{{ route('profile') }}" style="color: black;"><i class="fas fa-user"></i>
                        {{ __('main.profil') }}</a>
                    <a href="{{ route('transactions.history') }}"><i class="fas fa-list"></i>
                        {{ __('main.riwayat_transaksi') }}</a>
                    <a href="{{ route('admin.verification') }}"><i class="fas fa-check-circle"></i>
                        {{ __('main.verifikasi_admin') }}</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt"></i>
                            {{ __('main.logout') }}</button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card p-4">
                    <h5 class="mb-4">{{ __('main.profil_pengguna') }}</h5>

                    <!-- Form Update Profil -->
                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="profile-container">
                            <!-- Profile Details -->
                            <div class="profile-details">
                                <div class="mb-3">
                                    <label for="name" class="form-label">{{ __('main.nama') }}</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', auth()->user()->name) }}"
                                        placeholder="{{ __('main.masukkan_nama') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">{{ __('main.email') }}</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', auth()->user()->email) }}"
                                        placeholder="{{ __('main.masukkan_email') }}">
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">{{ __('main.telepon') }}</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                        placeholder="{{ __('main.masukkan_telepon') }}">
                                </div>
                                <button type="submit"
                                    class="btn btn-primary w-100 mt-3">{{ __('main.simpan_perubahan') }}</button>
                            </div>

                            <!-- Profile Picture -->
                            <div class="profile-pic-container">
                                <img id="profile-preview"
                                    src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : 'https://via.placeholder.com/120' }}"
                                    alt="{{ __('main.foto_profil') }}">
                                <label for="profile-pic" class="upload-icon">
                                    <i class="fas fa-camera"></i>
                                </label>
                                <input type="file" id="profile-pic" name="profile_picture" class="d-none"
                                    accept="image/*" onchange="previewImage(event)">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
