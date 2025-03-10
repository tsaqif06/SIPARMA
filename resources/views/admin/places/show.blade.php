@extends('admin.layout.layout')

@php
    $title = 'Lihat Tempat';
    $subTitle = 'Tempat - Lihat';
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
    <div class="container">
        <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base" style="padding: 20px 30px">

            <!-- Header Tempat -->
            <div class="place-header mb-5">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="fw-bold">{{ $place->name }}</h1>
                    </div>
                    <div class="col-md-4 text-end">
                        @php $bg = $place->status == 'Buka' ? 'success' : 'danger'; @endphp
                        <span
                            class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">
                            {{ ucfirst($place->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Informasi Utama -->
            <div class="place-info mb-5">
                <div class="row">
                    <div class="col-md-6">
                        <h3 class="fw-bold">Deskripsi</h3>
                        <p>{{ $place->description }}</p>
                        @php
                            $location = json_decode($place->location);
                        @endphp
                        <p>{{ $location->address }}</p>
                        <div class="preview-box text-center w-100">
                            <div id="map" class="border" style="height: 300px; width: 100%; border-radius: 7px;">
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ $location->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $location->longitude }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h3 class="fw-bold">Detail</h3>
                        <ul class="list-unstyled">
                            <li><strong>Tipe:</strong> {{ ucfirst($place->type) }}</li>
                            <li><strong>Jam Operasional:</strong> {{ $place->open_time }} - {{ $place->close_time }}</li>
                            <li><strong>Destinasi Terdekat:</strong>
                                {{ $place->destination ? $place->destination->name : '-' }}
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 mt-5">
                        <!-- Galeri Tempat -->
                        @if ($place->gallery->count() > 0)
                            <div class="place-gallery mb-5">
                                <h3 class="fw-bold">Galeri</h3>
                                <div class="row">
                                    @foreach ($place->gallery as $image)
                                        <div class="col-md-4 mb-3">
                                            <div class="gallery-image">
                                                <img src="{{ asset($image->image_url) }}" alt="Gambar {{ $place->name }}"
                                                    class="img-fluid rounded">
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi -->
            <div class="place-actions">
                @if (auth()->user()->role === 'superadmin')
                    <a href="{{ route('admin.places.index') }}" class="btn btn-secondary">Kembali</a>
                @endif
                <a href="{{ route('admin.places.edit', $place->id) }}" class="btn btn-primary">Edit</a>
            </div>
        </div>
    </div>
@endsection
