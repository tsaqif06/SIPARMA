@extends('admin.layout.layout')
@php
    $title = 'Edit Wisata';
    $subTitle = 'Wisata - Edit';

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

        const lat = parseFloat(document.getElementById("latitude").value) || -7.9666;
        const lng = parseFloat(document.getElementById("longitude").value) || 112.6326;

        const map = L.map("map").setView([lat, lng], 14);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "© OpenStreetMap contributors"
        }).addTo(map);

        // Tambahkan marker tanpa draggable
        L.marker([lat, lng], {
            icon: defaultIcon
        }).addTo(map);
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
                            <div class="row">
                                <!-- Nama Pengaju -->
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="user_id"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                            Pengaju<span class="text-danger-600">*</span></label>
                                        <input type="text"
                                            class="form-control radius-8 @error('user_id') is-invalid @enderror"
                                            id="user_id" name="user_id" placeholder="Masukkan Nama Destinasi"
                                            value="{{ old('user_id', $recommendation->user->name) }}" readonly>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Nama Destinasi -->
                                <div class="col-sm-6">
                                    <div class="mb-20">
                                        <label for="destination_name"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Nama
                                            Wisata<span class="text-danger-600">*</span></label>
                                        <input type="text"
                                            class="form-control radius-8 @error('destination_name') is-invalid @enderror"
                                            id="destination_name" name="destination_name"
                                            placeholder="Masukkan Nama Destinasi"
                                            value="{{ old('destination_name', $recommendation->destination_name) }}"
                                            readonly>
                                        @error('destination_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                @php
                                    $location = json_decode($recommendation->location);
                                @endphp

                                <!-- Lokasi -->
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="address"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Lokasi<span
                                                class="text-danger-600">*</span></label>
                                        <input type="text"
                                            class="form-control radius-8 @error('address') is-invalid @enderror"
                                            id="address" name="address" placeholder="Masukkan Lokasi"
                                            value="{{ old('address', $location->address) }}" readonly>
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
                                                value="{{ old('latitude', $location->latitude) }}">
                                            <input type="hidden" name="longitude" id="longitude"
                                                value="{{ old('longitude', $location->longitude) }}">

                                            <!-- Menampilkan pesan error jika latitude/longitude kosong -->
                                            @error('longitude')
                                                <div class="text-danger">Harap pilih lokasi di peta.</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                @php
                                    // Menentukan status dalam Bahasa Indonesia
                                    $statusIndo = '';
                                    if ($recommendation->status == 'pending') {
                                        $statusIndo = 'Menunggu'; // Pending = Menunggu
                                    } elseif ($recommendation->status == 'approved') {
                                        $statusIndo = 'Disetujui'; // Approved = Disetujui
                                    } elseif ($recommendation->status == 'rejected') {
                                        $statusIndo = 'Ditolak'; // Rejected = Ditolak
                                    }
                                @endphp

                                <!-- Status -->
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="status"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Status<span
                                                class="text-danger-600">*</span></label>
                                        <input type="text"
                                            class="form-control radius-8 @error('status') is-invalid @enderror"
                                            id="status" name="status" placeholder="Masukkan Nama Destinasi"
                                            value="{{ old('status', $statusIndo) }}" readonly>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Deskripsi -->
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="description"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Deskripsi<span
                                                class="text-danger-600">*</span></label>
                                        <textarea class="form-control radius-8 @error('description') is-invalid @enderror" id="description" name="description"
                                            placeholder="Masukkan Deskripsi Destinasi" rows="4" readonly>{{ old('description', $recommendation->description) }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Gallery Gambar -->
                                <div class="col-sm-12">
                                    <div class="mb-20">
                                        <label for="gallery"
                                            class="form-label fw-semibold text-primary-light text-sm mb-8">Gallery
                                            Gambar</label>
                                        <div class="row">
                                            @foreach ($recommendation->images as $image)
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <img src="{{ asset($image->image_url) }}" alt="Image"
                                                            class="img-fluid rounded"
                                                            style="max-height: 150px; object-fit: cover;">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <a href="{{ route('admin.destinations.recommendation') }}"
                                    class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                                <a href="{{ route('admin.recommendations.changeStatus', $recommendation->id) }}"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Ubah
                                    Status</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
