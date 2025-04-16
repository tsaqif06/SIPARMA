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
    <!-- start of hero -->
    <section class="hero-section">
        <div class="hero-wraper hero-slide">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-10 col-12">
                        <div class="hero-content-slider">
                            <div class="item">
                                <h2 class="wow fadeInUp" data-wow-duration="1400ms">{{ __('main.booking_tiket') }} &
                                    {{ __('main.eksplor_wisata') }}</h2>
                                <p class="wow fadeInUp" data-wow-duration="1600ms">{{ __('main.temukan_wisata') }}</p>
                                <div class="hero-btn wow fadeInUp" data-wow-duration="1800ms">
                                    <a href="#promo" class="theme-btn">{{ __('main.jelajahi_lebih') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-content">
                <div class="hero-img">
                    <div class="item">
                        <img src="{{ asset('assets/user/images/slider/slide-new.jpg') }}" style="object-fit: cover;"
                            alt="">
                    </div>
                </div>
                <div class="side-img-2">
                    <img src="{{ asset('assets/user/images/slider/side-img-2.jpg') }}" loading="lazy" alt="">
                </div>
                <div class="side-img-3">
                    <img src="{{ asset('assets/user/images/slider/side-img-3.jpg') }}" loading="lazy" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- end of hero -->

    <!-- start of promo-places -->
    @if ($promos['destinations']->isNotEmpty())
        <section class="promo-places-section section-padding" id="promo">
            <div class="container">
                <div class="row">
                    <div class="col-lg-5 col-12">
                        <div class="wpo-section-title wow fadeInLeft">
                            <span class="text-start"># {{ __('main.penawaran_terbaik') }}</span>
                            <h2>{{ __('main.penawaran_spesial') }}</h2>
                        </div>
                    </div>

                    <div class="col-lg-7 col-12">
                        <div class="popular-slider owl-carousel">
                            @foreach ($promos['destinations'] as $promo)
                                @php
                                    $diskonPersen = $promo->promos[0]->discount ?? 0;
                                    $hargaDiskon = $promo->price - ($promo->price * $diskonPersen) / 100;
                                @endphp
                                <div class="places-item wow fadeInRight">
                                    <div class="image"
                                        style="background-image: url('{{ $promo->gallery[0]->image_url ?? '' }}');">
                                    </div>
                                    <div class="content">
                                        <h2>
                                            <a
                                                href="{{ route('destination.show', $promo->slug) }}">{{ $promo->getTranslatedName() }}</a>
                                        </h2>
                                        <span class="price-old">IDR
                                            {{ number_format($promo->price, 0, ',', '.') }}</span><br>
                                        <span class="text-price" style="color: #ff8000;">IDR
                                            {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                        <p class="card-text">
                                            {{ __('main.rating') }}: {{ number_format($promo->reviews_avg_rating, 1) }} /
                                            5
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <!-- end of promo-places -->

    <!-- start of featured-->
    <section class="featured-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">
                    <div class="wpo-section-title s2 wow fadeInDown">
                        <span># {{ __('main.jelajahi_malang') }}</span>
                        <h2>{{ __('main.temukan_keindahan') }}</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-xs-12 sortable-gallery">
                    <div class="gallery-filters wow fadeInDown">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <ul class="category-item">
                                    <li>
                                        <a data-filter=".alams" href="#" class="featured-btn current">
                                            {{ __('main.alam') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a data-filter=".wahanas" href="#" class="featured-btn">
                                            {{ __('main.wahana') }}
                                        </a>
                                    </li>
                                    <li>
                                        <a data-filter=".places" href="#" class="featured-btn">
                                            {{ __('main.tempat') }}
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="gallery-container gallery-fancybox masonry-gallery row">
                @foreach ($categories as $type => $destinations)
                    @foreach ($destinations as $destination)
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid {{ strtolower($type) }} wow zoomIn"
                            data-wow-duration="2000ms">
                            @if ($type != 'places')
                                <a href="{{ route('destination.show', $destination->slug) }}" class="text-decoration-none">
                                @else
                                    <a href="{{ route('place.show', $destination->slug) }}" class="text-decoration-none">
                            @endif
                            <div class="featured-card d-flex flex-column h-100" style="min-height: 500px;">
                                <div class="image">
                                    <div class="img bg-cover lazy-bg"
                                        data-bg="{{ $destination->gallery[0]->image_url ?? asset('assets/images/default.png') }}"
                                        style="height: 250px;">
                                    </div>
                                </div>
                                <div class="content flex-grow-1 d-flex flex-column">
                                    <h2 class="fs-5 text-dark">
                                        {{ $destination->getTranslatedName() }}
                                    </h2>
                                    <div class="description flex-grow-1 d-flex align-items-start"
                                        style="min-height: 100px;">
                                        <span class="overflow-hidden"
                                            style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                            {{ $destination->getTranslatedDescription() ?? __('main.deskripsi_tidak_tersedia') }}
                                        </span>
                                    </div>
                                    <div class="top-content mt-auto">
                                        <ul class="list-unstyled d-flex justify-content-between gap-2">
                                            <li class="text-center">
                                                @if ($type != 'places')
                                                    <span>{{ $destination->price ? number_format($destination->price, 0, ',', '.') : 'N/A' }}</span>
                                                    <span class="d-block small"
                                                        style="color: gray">{{ __('main.harga') }}</span>
                                                @else
                                                    <span>{{ number_format($destination->facilities->count() ?? '0', 0, ',', '.') }}</span>
                                                    <span class="d-block small"
                                                        style="color: gray">{{ __('main.fasilitas') }}</span>
                                                @endif
                                            </li>
                                            <li class="text-center">
                                                <span>{{ number_format($destination->reviews->count() ?? '0', 0, ',', '.') }}</span>
                                                <span class="d-block small"
                                                    style="color: gray">{{ __('main.ulasan') }}</span>
                                            </li>
                                            <li class="text-center">
                                                <span>{{ number_format($destination->reviews_avg_rating, 1) ?? '0.0' }}</span>
                                                <span class="d-block small"
                                                    style="color: gray">{{ __('main.rating') }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="featured-all-btn wow zoomIn">
                <a href="#most-rating" class="theme-btn-s2">{{ __('main.jelajahi_lebih') }}</a>
            </div>
        </div>
    </section>
    <!-- end of featured-->

    <!-- start of blog -->
    <section class="blog-section section-padding" id="most-rating">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg- col-12">
                    <div class="wpo-section-title s2 wow fadeInUp">
                        <span># {{ __('main.wisata_terbaik') }}</span>
                        <h2>{{ __('main.jelajahi_destinasi') }}</h2>
                    </div>
                </div>
                <div class="col-lg-10 col-12">
                    <div class="authorlist-wrap wow fadeInUp">
                        <div class="row">
                            @foreach ($topRatedDestinations as $destination)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <a href="{{ route('destination.show', $destination->slug) }}"
                                        class="text-decoration-none">
                                        <div class="auth-card">
                                            <div class="shape">
                                                <svg viewBox="0 0 250 246" fill="none">
                                                    <path
                                                        d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                                </svg>
                                            </div>
                                            <div class="image">
                                                <div class="img lazy-bg"
                                                    data-bg="{{ $destination->gallery[0]->image_url ?? 'assets/images/default.png' }}">
                                                </div>
                                            </div>
                                            <div class="content">
                                                <div class="rating">
                                                    {{ __('main.rating') }}:
                                                    {{ number_format($destination->reviews_avg_rating, 1) }} (<img
                                                        src="{{ asset('assets/user/images/authorlist/star.svg') }}"
                                                        alt="" loading="lazy">)
                                                </div>
                                                <h2>{{ $destination->getTranslatedName() }}</h2>
                                                <h4>Rp {{ number_format($destination->price, 0, ',', '.') }}</h4>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end of blog -->

    <!-- start of authorlist-->
    <section class="authorlist-section section-padding" style="margin-bottom: -10%;">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-xl-4 col-12">
                    <div class="wpo-section-title s2 wow fadeInLeftSlow" data-wow-duration="1700ms">
                        <p style="color: #FFFFFF;"># {{ __('main.wisata_terbaik') }}</p>
                        <h2 style="margin-top: 20px; color: #FFFFFF;">{{ __('main.beritahu_kami') }}</h2>
                        <p style="margin: 20px 0 0 0; color: #FFFFFF;">{{ __('main.rekomendasi_deskripsi') }}</p>
                    </div>
                </div>
                <div class="offset-xl-2 col-xl-6 col-12">
                    <div class="container-form wow fadeInRightSlow" data-wow-duration="1700ms">
                        <form action="{{ route('home.recommendation.store') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <div class="mb-4">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="{{ __('main.nama_wisata') }}"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" placeholder="{{ __('main.alamat') }}"
                                            value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" id="description @error('description') is-invalid @enderror" name="description"
                                            rows="3" placeholder="{{ __('main.deskripsi') }}">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label style="color: black" for="image_url"
                                            class="form-label">{{ __('main.upload_gambar') }}</label>
                                        <input class="form-control @error('image_url') is-invalid @enderror"
                                            type="file" id="image_url" name="image_url[]" multiple>
                                        @error('image_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Map Section -->
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="preview-box text-center w-100">
                                        <span class="text-muted">{{ __('main.tentukan_lokasi') }}</span>
                                        <div id="map"
                                            class="border @error('latitude') border-danger @enderror @error('longitude') border-danger @enderror"
                                            style="height: 250px; width: 100%; border-radius: 7px;"></div>

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
                            @auth
                                <button type="submit" class="btn btn-primary">{{ __('main.kirim') }}</button>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}">
                                    <button type="button" class="btn btn-primary">{{ __('main.kirim') }}</button>
                                </a>
                            @endguest
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end of authorlist-->
@endsection
