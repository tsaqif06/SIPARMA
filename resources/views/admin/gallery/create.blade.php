@extends('admin.layout.layout')
@php
    $title = 'Tambah Gambar';
    $subTitle = 'Galeri - Tambah';
    $script = '<script>
        document.getElementById("image_url").addEventListener("change", function(event) {
            const file = event.target.files[0]; // Ambil file yang diunggah
            const preview = document.getElementById("imagePreview"); // Ambil elemen preview

            if (file) {
                const reader = new FileReader(); // Buat objek FileReader

                // Saat file selesai dibaca, tampilkan preview gambar
                reader.onload = function(e) {
                    preview.src = e.target.result; // Set src gambar ke hasil baca file
                    preview.style.display = "block"; // Tampilkan elemen preview
                };

                reader.readAsDataURL(file); // Baca file sebagai URL data
            } else {
                preview.src = "#"; // Reset src gambar
                preview.style.display = "none"; // Sembunyikan elemen preview
            }
        });
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
                            <!-- Upload Image Start -->
                            <form action="{{ route('admin.gallery.store', $type) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Image Type -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="image_type"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Tipe Gambar<span class="text-danger-600">*</span>
                                            </label>
                                            <select class="form-control radius-8 @error('image_type') is-invalid @enderror"
                                                id="image_type" name="image_type" required>
                                                <option value="">Pilih Tipe Gambar</option>
                                                <option value="place" {{ old('image_type') == 'place' ? 'selected' : '' }}>
                                                    Tempat</option>
                                                <option value="promo" {{ old('image_type') == 'promo' ? 'selected' : '' }}>
                                                    Promo</option>
                                                @if ($type === 'place')
                                                    <option value="menu"
                                                        {{ old('image_type') == 'menu' ? 'selected' : '' }}>
                                                        Menu</option>
                                                @endif
                                            </select>
                                            @error('image_type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Image URL -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="image_url"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">
                                                Unggah Gambar<span class="text-danger-600">*</span>
                                            </label>
                                            <input type="file"
                                                class="form-control radius-8 @error('image_url') is-invalid @enderror"
                                                id="image_url" name="image_url" required>
                                            @error('image_url')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="mt-3">
                                            <img id="imagePreview" src="#" alt="Preview Gambar"
                                                style="display: none; max-width: 100%; max-height: 200px; border-radius: 8px;" />
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                        Simpan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
