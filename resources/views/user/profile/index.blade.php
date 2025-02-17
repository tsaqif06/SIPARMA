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
                    <div class="sidebar-title text-secondary">Menu</div>
                    <a href="{{ route('profile') }}" style="color: black;"><i class="fas fa-user"></i> Profil</a>
                    <a href="{{ route('transactions.history') }}"><i class="fas fa-list"></i> Riwayat Transaksi</a>
                    <a href="{{ route('admin.verification') }}"><i class="fas fa-check-circle"></i> Verifikasi Admin</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt"></i>
                            Logout</button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card p-4">
                    <h5 class="mb-4">Profil Pengguna</h5>

                    <!-- Form Update Profil -->
                    <form id="profile-form" method="POST" action="{{ route('profile.update') }}"
                        enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="profile-container">
                            <!-- Profile Details -->
                            <div class="profile-details">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        value="{{ old('name', auth()->user()->name) }}" placeholder="Masukkan nama">
                                </div>
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', auth()->user()->email) }}" placeholder="Masukkan email">
                                </div>
                                <div class="mb-3">
                                    <label for="phone_number" class="form-label">Telepon</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number"
                                        value="{{ old('phone_number', auth()->user()->phone_number) }}"
                                        placeholder="Masukkan nomor telepon">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-3">Simpan Perubahan</button>
                            </div>

                            <!-- Profile Picture -->
                            <div class="profile-pic-container">
                                <img id="profile-preview"
                                    src="{{ auth()->user()->profile_picture ? asset(auth()->user()->profile_picture) : 'https://via.placeholder.com/120' }}"
                                    alt="Profile Picture">
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
