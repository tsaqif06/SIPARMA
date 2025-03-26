@extends('user.layouts.app')

@php
    $script = '<script>
        // Fix marker default yang rusak
        const defaultIcon = L.icon({
            iconUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-icon.png",
            shadowUrl: "https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.7.1/images/marker-shadow.png",
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        // Ambil nilai latitude dan longitude dari input hidden
        const latInput = document.getElementById("latitude").value;
        const lngInput = document.getElementById("longitude").value;

        // Cek apakah ada nilai lama (old value) atau pakai default
        const lat = latInput ? parseFloat(latInput) : -7.9666;
        const lng = lngInput ? parseFloat(lngInput) : 112.6326;

        // Inisialisasi Peta dengan posisi dari old value atau default
        const map = L.map("map").setView([lat, lng], 10);

        // Tambahkan Tile Layer dari OpenStreetMap
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors"
        }).addTo(map);

        // Tambahkan Marker
        const marker = L.marker([lat, lng], {
            draggable: true,
            icon: defaultIcon
        }).addTo(map);

        // Ketika marker dipindahkan
        marker.on("dragend", function(event) {
            const position = marker.getLatLng();
            document.getElementById("latitude").value = position.lat;
            document.getElementById("longitude").value = position.lng;
        });

        // Ketika peta diklik, pindahkan marker ke lokasi yang diklik
        map.on("click", function(event) {
            const position = event.latlng;
            marker.setLatLng(position);
            document.getElementById("latitude").value = position.lat;
            document.getElementById("longitude").value = position.lng;
        });

        // Autocomplete untuk input alamat (opsional, menggunakan Nominatim)
        document.getElementById("address").addEventListener("change", function() {
            const address = this.value;
            if (address) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const lat = parseFloat(data[0].lat);
                            const lon = parseFloat(data[0].lon);
                            map.setView([lat, lon], 15);
                            marker.setLatLng([lat, lon]);
                            document.getElementById("latitude").value = lat;
                            document.getElementById("longitude").value = lon;
                        }
                    })
                    .catch(error => console.error("Error:", error));
            }
        });
    </script>
';
@endphp

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-title text-secondary">{{ __('main.menu') }}</div>
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i>
                        {{ __('main.profil') }}</a>
                    <a href="{{ route('transactions.history') }}"><i class="fas fa-list"></i>
                        {{ __('main.riwayat_transaksi') }}</a>
                    <a href="{{ route('admin.verification') }}" style="color: black;"><i class="fas fa-check-circle"></i>
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
                    <h3>{{ __('main.verifikasi_admin_tempat') }}</h3>
                    <hr class="mb-4">

                    @if ($adminPlace && $adminPlace->approval_status == 'pending')
                        <h3 class="text-center text-secondary">{{ __('main.verifikasi_diproses') }}</h3>
                        <p class="text-center text-muted">{{ __('main.verifikasi_diproses_pesan') }}</p>
                    @elseif ($adminPlace && $adminPlace->approval_status == 'approved')
                        <h3 class="text-center text-success">{{ __('main.verifikasi_berhasil') }}</h3>
                        <p class="text-center text-muted">{{ __('main.verifikasi_berhasil_pesan') }}</p>

                        <form id="adminLogoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>

                        <button class="btn btn-primary w-100 mt-3" onclick="convertRoleAndRefresh()">
                            {{ __('main.masuk_admin') }}
                        </button>

                        <script>
                            function convertRoleAndRefresh() {
                                fetch("{{ route('convert.role') }}", {
                                        method: "POST",
                                        headers: {
                                            "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute("content"),
                                            "Content-Type": "application/json",
                                        },
                                        body: JSON.stringify({})
                                    })
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.success) {
                                            location.reload();
                                        } else {
                                            alert("{{ __('main.gagal_ubah_role') }}: " + data.message);
                                        }
                                    })
                                    .catch(error => console.error("Error:", error));
                            }
                        </script>
                    @else
                        @if ($adminPlace && $adminPlace->approval_status == 'rejected')
                            <div class="alert alert-danger text-center">
                                <strong>{{ __('main.pengajuan_ditolak') }}</strong><br>
                                {{ __('main.pengajuan_ulang_pesan') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.verification.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <div class="mb-3">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="{{ __('main.nama_tempat') }}" value="{{ old('name') }}"
                                            required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="type"
                                            class="form-control @error('type') is-invalid @enderror"
                                            placeholder="{{ __('main.tipe_tempat') }}" value="{{ old('type') }}"
                                            required>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="address" id="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            placeholder="{{ __('main.alamat') }}" value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <select name="destination_id"
                                            class="form-control @error('destination_id') is-invalid @enderror w-100"
                                            required>
                                            <option value="" disabled selected>{{ __('main.pilih_wisata') }}</option>
                                            @foreach ($destinations as $destination)
                                                <option value="{{ $destination->id }}"
                                                    {{ old('destination_id') == $destination->id ? 'selected' : '' }}>
                                                    {{ $destination->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('destination_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 mt-1">
                                        <label for="imageUpload" class="form-label">{{ __('main.unggah_bukti') }}</label>
                                        <input type="file" name="ownership_docs"
                                            class="form-control @error('ownership_docs') is-invalid @enderror"
                                            id="imageUpload" required>
                                        @error('ownership_docs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3 mt-1">
                                        <label for="galleryImages" class="form-label">{{ __('main.unggah_foto') }}</label>
                                        <input type="file" name="gallery_images[]"
                                            class="form-control @error('gallery_images') is-invalid @enderror"
                                            id="galleryImages" multiple>
                                        @error('gallery_images')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Map Section -->
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="preview-box text-center w-100">
                                        <span class="text-muted">{{ __('main.pilih_lokasi_peta') }}</span>
                                        <div id="map"
                                            class="border @error('latitude') border-danger @enderror @error('longitude') border-danger @enderror"
                                            style="height: 300px; width: 100%; border-radius: 7px;"></div>

                                        <!-- Hidden Input for Latitude & Longitude -->
                                        <input type="hidden" name="latitude" id="latitude"
                                            value="{{ old('latitude', '-7.9666') }}">
                                        <input type="hidden" name="longitude" id="longitude"
                                            value="{{ old('longitude', '112.6326') }}">

                                        @error('longitude')
                                            <div class="text-danger">{{ __('main.harap_pilih_lokasi') }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">{{ __('main.kirim') }}</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
