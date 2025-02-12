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
                    <div class="sidebar-title text-secondary">Menu</div>
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profil</a>
                    <a href="{{ route('transactions.history') }}"><i class="fas fa-list"></i> Riwayat Transaksi</a>
                    <a href="{{ route('admin.verification') }}" style="color: black;"><i class="fas fa-check-circle"></i>
                        Verifikasi Admin</a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card p-4">
                    <h3>Verifikasi Admin Tempat</h3>
                    <hr class="mb-4">

                    @php
                        $adminPlace = \App\Models\AdminPlace::where('user_id', auth()->user()->id)->first();
                    @endphp

                    @if ($adminPlace && $adminPlace->approval_status == 'pending')
                        <h3 class="text-center text-secondary">Verifikasi Sedang Diproses</h3>
                        <p class="text-center text-muted">Permintaan Anda sedang ditinjau oleh admin. Mohon tunggu hingga
                            proses verifikasi selesai.</p>
                    @elseif ($adminPlace && $adminPlace->approval_status == 'approved')
                        <h3 class="text-center text-success">Verifikasi Berhasil</h3>
                        <p class="text-center text-muted">Selamat! Akun Anda telah disetujui sebagai admin tempat.</p>

                        <form id="adminLogoutForm" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>

                        <button class="btn btn-primary w-100 mt-3" onclick="redirectToAdminLogin()">
                            Masuk ke Admin
                        </button>

                        <script>
                            function redirectToAdminLogin() {
                                document.getElementById('adminLogoutForm').submit();
                                setTimeout(() => {
                                    window.location.href = "{{ route('admin.login') }}";
                                }, 500);
                            }
                        </script>
                    @else
                        @if ($adminPlace && $adminPlace->approval_status == 'rejected')
                            <div class="alert alert-danger text-center">
                                <strong>Pengajuan Anda Sebelumnya Telah Ditolak.</strong><br>
                                Anda masih bisa mengajukan ulang jika ingin mencoba kembali.
                            </div>
                        @endif

                        <form action="{{ route('admin.verification.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <div class="mb-3">
                                        <input type="text" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            placeholder="Nama Tempat" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="type"
                                            class="form-control @error('type') is-invalid @enderror"
                                            placeholder="Tipe tempat (Restoran, Penginapan, dll)"
                                            value="{{ old('type') }}" required>
                                        @error('type')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <input type="text" name="address" id="address"
                                            class="form-control @error('address') is-invalid @enderror" placeholder="Alamat"
                                            value="{{ old('address') }}" required>
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <select name="destination_id"
                                            class="form-control @error('destination_id') is-invalid @enderror w-100"
                                            required>
                                            <option value="" disabled selected>Pilih Wisata Terdekat</option>
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
                                        <label for="imageUpload" class="form-label">Unggah Bukti Kepemilikan</label>
                                        <input type="file" name="ownership_docs"
                                            class="form-control @error('ownership_docs') is-invalid @enderror"
                                            id="imageUpload" required>
                                        @error('ownership_docs')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bagian Map -->
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="preview-box text-center w-100">
                                        <span class="text-muted">Silakan pilih titik lokasi tempat Anda di peta</span>
                                        <div id="map"
                                            class="border @error('latitude') border-danger @enderror @error('longitude') border-danger @enderror"
                                            style="height: 300px; width: 100%;"></div>

                                        <!-- Input Hidden untuk Latitude & Longitude -->
                                        <input type="hidden" name="latitude" id="latitude"
                                            value="{{ old('latitude', '-7.9666') }}">
                                        <input type="hidden" name="longitude" id="longitude"
                                            value="{{ old('longitude', '112.6326') }}">

                                        <!-- Menampilkan pesan error jika latitude/longitude kosong -->
                                        @error('longitude')
                                            <div class="text-danger">Harap pilih lokasi di peta.</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary w-100 mt-3">Kirim</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
