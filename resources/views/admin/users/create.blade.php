@extends('admin.layout.layout')
@php
    $title = 'Edit Profil';
    $subTitle = 'Profil - Edit';
    $script = '<script>
        // ======================== Upload Image Start =====================
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#imagePreview").css("background-image", "url(" + e.target.result + ")");
                    $("#imagePreview").hide();
                    $("#imagePreview img").css("visibility", "hidden");
                    $("#imagePreview").fadeIn(650);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $("#imageUpload").change(function() {
            readURL(this);
        });
        // ======================== Upload Image End =====================

        // ================== Password Show Hide Js Start ==========
        function initializePasswordToggle(toggleSelector) {
            $(toggleSelector).on("click", function() {
                $(this).toggleClass("ri-eye-off-line");
                var input = $($(this).attr("data-toggle"));
                if (input.attr("type") === "password") {
                    input.attr("type", "text");
                } else {
                    input.attr("type", "password");
                }
            });
        }
        // Call the function
        initializePasswordToggle(".toggle-password");
        // ========================= Password Show Hide Js End ===========================

        // ========================= Handle Destination/Place ===========================
        $("#role").on("change", function() {
            var role = $(this).val();

            $("#destination-field").hide();
            $("#place-field").hide();

            if (role === "admin_wisata") {
                $("#destination-field").show();
            } else if (role === "admin_tempat") {
                $("#place-field").show();
            }
        });

        $("#role").trigger("change");
        // ========================= Handle Destination/Place End ===========================
    </script>
    ';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    {{--  <ul class="nav border-gradient-tab nav-pills mb-20 d-inline-flex" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24 active" id="pills-edit-profile-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-edit-profile" type="button" role="tab"
                                aria-controls="pills-edit-profile" aria-selected="true">
                                Edit Profile
                            </button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center px-24" id="pills-change-passwork-tab"
                                data-bs-toggle="pill" data-bs-target="#pills-change-passwork" type="button" role="tab"
                                aria-controls="pills-change-passwork" aria-selected="false" tabindex="-1">
                                Change Password
                            </button>
                        </li>
                    </ul> --}}

                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <h6 class="text-md text-primary-light mb-16">Foto Profil</h6>
                            <!-- Upload Image Start -->
                            <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="mb-24 mt-16">
                                    <div class="avatar-upload">
                                        <div
                                            class="avatar-edit position-absolute bottom-0 end-0 me-24 mt-16 z-1 cursor-pointer">
                                            <input type='file' id="imageUpload" name="profile_picture"
                                                accept=".png, .jpg, .jpeg" hidden>
                                            <label for="imageUpload"
                                                class="w-32-px h-32-px d-flex justify-content-center align-items-center bg-primary-50 text-primary-600 border border-primary-600 bg-hover-primary-100 text-lg rounded-circle">
                                                <iconify-icon icon="solar:camera-outline" class="icon"></iconify-icon>
                                            </label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview"></div>
                                        </div>
                                    </div>
                                    @error('profile_picture')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!-- Upload Image End -->

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="Masukkan Nama"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="email"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Email <span
                                                    class="text-danger-600">*</span></label>
                                            <input type="email"
                                                class="form-control radius-8 @error('email') is-invalid @enderror"
                                                id="email" name="email" placeholder="Masukkan Email"
                                                value="{{ old('email') }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="password"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Password
                                                <span class="text-danger-600">*</span></label>
                                            <div class="position-relative">
                                                <input type="password"
                                                    class="form-control radius-8 @error('password') is-invalid @enderror"
                                                    id="password" name="password" placeholder="Masukkan Password*">
                                                <span
                                                    class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                                    data-toggle="#password"></span>
                                                @error('password')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="phone_number"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nomor
                                                Telepon<span class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('phone_number') is-invalid @enderror"
                                                id="phone_number" name="phone_number" placeholder="Masukkan Nomor Telepon"
                                                value="{{ old('phone_number') }}" required>
                                            @error('phone_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="role"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Role<span
                                                    class="text-danger-600">*</span></label>
                                            <select
                                                class="form-control radius-8 form-select @error('role') is-invalid @enderror"
                                                id="role" name="role" required>
                                                <option value="">Pilih Role</option>
                                                <option value="admin_wisata"
                                                    {{ old('role') == 'admin_wisata' ? 'selected' : '' }}>Admin Wisata
                                                </option>
                                                <option value="admin_tempat"
                                                    {{ old('role') == 'admin_tempat' ? 'selected' : '' }}>Admin Tempat
                                                </option>
                                            </select>
                                            @error('role')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Destination Field (only show for admin_wisata) -->
                                    <div class="col-sm-6" id="destination-field" style="display: none;">
                                        <div class="mb-20">
                                            <label for="destination_id"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Destinasi
                                                Wisata<span class="text-danger-600">*</span></label>
                                            <select
                                                class="form-control radius-8 form-select @error('destination_id') is-invalid @enderror"
                                                id="destination_id" name="destination_id">
                                                <option value="">Pilih Destinasi</option>
                                                @foreach ($destinations as $destination)
                                                    <option value="{{ $destination->id }}"
                                                        {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                                        {{ $destination->id }} - {{ $destination->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('destination_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Place Field (only show for admin_tempat) -->
                                    <div class="col-sm-6" id="place-field" style="display: none;">
                                        <div class="mb-20">
                                            <label for="place_id"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tempat<span
                                                    class="text-danger-600">*</span></label>
                                            <select
                                                class="form-control radius-8 form-select @error('place_id') is-invalid @enderror"
                                                id="place_id" name="place_id">
                                                <option value="">Pilih Tempat</option>
                                                @foreach ($places as $place)
                                                    <option value="{{ $place->id }}"
                                                        {{ old('place_id') == $place->id ? 'selected' : '' }}>
                                                        {{ $place->id }} - {{ $place->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('place_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Save</button>
                                </div>
                            </form>
                        </div>
                        {{--  <div class="tab-pane fade" id="pills-change-passwork" role="tabpanel"
                            aria-labelledby="pills-change-passwork-tab" tabindex="0">
                            <div class="mb-20">
                                <label for="your-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">New Password <span
                                        class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control radius-8" id="your-password"
                                        placeholder="Enter New Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#your-password"></span>
                                </div>
                            </div>
                            <div class="mb-20">
                                <label for="confirm-password"
                                    class="form-label fw-semibold text-primary-light text-sm mb-8">Confirmed Password <span
                                        class="text-danger-600">*</span></label>
                                <div class="position-relative">
                                    <input type="password" class="form-control radius-8" id="confirm-password"
                                        placeholder="Confirm Password*">
                                    <span
                                        class="toggle-password ri-eye-line cursor-pointer position-absolute end-0 top-50 translate-middle-y me-16 text-secondary-light"
                                        data-toggle="#confirm-password"></span>
                                </div>
                            </div>
                        </div>  --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
