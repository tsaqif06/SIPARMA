@extends('admin.layout.layout')
@php
    $title = 'Tambah Wisata';
    $subTitle = 'Wisata - Tambah';

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
        const map = L.map("map").setView([lat, lng], 12);

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
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-edit-profile" role="tabpanel"
                            aria-labelledby="pills-edit-profile-tab" tabindex="0">
                            <!-- Upload Image Start -->
                            <form action="{{ route('admin.destinations.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <!-- Nama Destinasi -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="name"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                                Wisata<span class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('name') is-invalid @enderror"
                                                id="name" name="name" placeholder="Masukkan Nama Destinasi"
                                                value="{{ old('name') }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Tipe Destinasi -->
                                    <div class="col-sm-6">
                                        <div class="mb-20">
                                            <label for="type"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Tipe
                                                Destinasi<span class="text-danger-600">*</span></label>
                                            <select class="form-control radius-8 @error('type') is-invalid @enderror"
                                                id="type" name="type" required>
                                                <option value="">Pilih Tipe</option>
                                                <option value="alam" {{ old('type') == 'alam' ? 'selected' : '' }}>Alam
                                                </option>
                                                <option value="wahana" {{ old('type') == 'wahana' ? 'selected' : '' }}>
                                                    Wahana</option>
                                            </select>
                                            @error('type')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Lokasi -->
                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <label for="address"
                                                class="form-label fw-semibold text-primary-light text-sm mb-8">Lokasi<span
                                                    class="text-danger-600">*</span></label>
                                            <input type="text"
                                                class="form-control radius-8 @error('address') is-invalid @enderror"
                                                id="address" name="address" placeholder="Masukkan Lokasi"
                                                value="{{ old('address') }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-sm-12">
                                        <div class="mb-20">
                                            <div class="preview-box text-center w-100">
                                                <span class="text-muted">Silakan pilih titik lokasi tempat Anda di
                                                    peta</span>
                                                <div id="map"
                                                    class="border @error('latitude') border-danger @enderror @error('longitude') border-danger @enderror"
                                                    style="height: 300px; width: 100%; border-radius: 7px;"></div>

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
                                </div>

                                <!-- Tombol Submit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <button type="submit"
                                        class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
