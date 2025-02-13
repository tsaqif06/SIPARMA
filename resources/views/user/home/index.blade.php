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
                                <h2>Booking Tiket & Eksplor Wisata Terbaik di Malang Raya</h2>
                                <p>Temukan wisata terbaik di Malang! Pesan tiket dengan mudah,
                                    jelajahi destinasi favorit, dan nikmati liburan tanpa ribet.</p>
                                <div class="hero-btn">
                                    <a href="#promo" class="theme-btn">Jelajahi Lebih Banyak</a>
                                </div>
                                {{--  <h2 class="wow fadeInUp" data-wow-duration="1400ms">Booking Tiket & Eksplor Wisata Terbaik di Malang Raya</h2>
                                    <p class="wow fadeInUp" data-wow-duration="1600ms">Temukan wisata terbaik di Malang! Pesan tiket dengan mudah,
                                        jelajahi destinasi favorit, dan nikmati liburan tanpa ribet.</p>
                                    <div class="hero-btn wow fadeInUp" data-wow-duration="1800ms">
                                        <a href="hotel-single.html" class="theme-btn">Jelajahi Lebih Banyak</a>
                                    </div>  --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="right-content">
                <div class="hero-img-slider">
                    <div class="item">
                        <img src="{{ asset('assets/user/images/slider/slide-new.jpg') }}" style="object-fit: cover;"
                            alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/user/images/slider/slide-new.jpg') }}" style="object-fit: cover;"
                            alt="">
                    </div>
                    <div class="item">
                        <img src="{{ asset('assets/user/images/slider/slide-new.jpg') }}" style="object-fit: cover;"
                            alt="">
                    </div>
                </div>
                <div class="side-img-2">
                    <img src="{{ asset('assets/user/images/slider/side-img-2.jpg') }}" alt="">
                </div>
                <div class="side-img-3">
                    <img src="{{ asset('assets/user/images/slider/side-img-3.jpg') }}" alt="">
                </div>
            </div>
        </div>
    </section>
    <!-- end of hero -->

    <!-- start of promo-places -->
    <section class="promo-places-section section-padding" id="promo">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-12">
                    <div class="wpo-section-title">
                        <span>// Penawaran Terbaik</span>
                        <h2>Penawaran Spesial</h2>
                    </div>
                </div>

                <div class="col-lg-7 col-12">
                    <div class="popular-slider owl-carousel">
                        @foreach ($promos['destinations'] as $promo)
                            @php
                                $diskonPersen = $promo->promos[0]->discount;
                                $hargaDiskon = $promo->price - ($promo->price * $diskonPersen) / 100;
                            @endphp
                            <div class="places-item">
                                <div class="image" style="background-image: url('{{ $promo->gallery[0]->image_url }}');">
                                </div>
                                <div class="content">
                                    <h2>
                                        <a href="{{ route('destination.show', $promo->slug) }}">{{ $promo->name }}</a>
                                    </h2>
                                    <span class="price-old">IDR {{ number_format($promo->price, 0, ',', '.') }}</span><br>
                                    <span>IDR {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                    <p class="card-text">
                                        Rating: {{ number_format($promo->reviews_avg_rating, 1) }} / 5
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- end of promo-places -->

    <!-- start of featured-->
    <section class="featured-section section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10 col-12">
                    <div class="wpo-section-title s2">
                        <span>// Jelajahi Malang</span>
                        <h2>Temukan Keindahan <br>di Malang Raya</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col col-xs-12 sortable-gallery">
                    <div class="gallery-filters">
                        <div class="row justify-content-center">
                            <div class="col-lg-4">
                                <ul class="category-item">
                                    <li>
                                        <a data-filter=".alams" href="#" class="featured-btn current">
                                            Alam
                                        </a>
                                    </li>
                                    <li>
                                        <a data-filter=".wahanas" href="#" class="featured-btn">
                                            Wahana
                                        </a>
                                    </li>
                                    <li>
                                        <a data-filter=".places" href="#" class="featured-btn">
                                            Tempat
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
                        <div class="col-xl-3 col-lg-4 col-md-6 col-12 custom-grid {{ strtolower($type) }} zoomIn"
                            data-wow-duration="2000ms">
                            <div class="featured-card">
                                <div class="image">
                                    <div class="img"
                                        style="background-image: url('{{ $destination->gallery[0]->image_url ?? 'https://picsum.photos/200.webp' }}');">
                                    </div>
                                    {{--  <img src="{{ $destination->gallery[0]->image_url ?? 'https://picsum.photos/200.webp' }}"
                                        alt="{{ $destination->name }}">  --}}
                                </div>
                                <div class="content">
                                    <h2>
                                        <a
                                            href="{{ route('destination.show', $destination->slug) }}">{{ $destination->name }}</a>
                                    </h2>
                                    <span>{{ Str::limit($destination->description ?? 'Deskripsi tidak tersedia.', 100, '...') }}</span>
                                    <div class="top-content">
                                        <ul>
                                            <li>
                                                <span>
                                                    {{ $destination->price ? number_format($destination->price, 0, ',', '.') : 'N/A' }}</span>
                                                <span class="date">Harga</span>
                                            </li>
                                            <li>
                                                <span>{{ $destination->reviews_count ?? '0' }}</span>
                                                <span class="date">Ulasan</span>
                                            </li>
                                            <li>
                                                <span>{{ number_format($destination->reviews_avg_rating, 1) ?? '0.0' }}</span>
                                                <span class="date">Rating</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endforeach
            </div>
            <div class="featured-all-btn">
                <a href="#most-rating" class="theme-btn-s2">Jelajahi Lebih Banyak</a>
            </div>
        </div>
    </section>
    <!-- end of featured-->

    <!-- start of blog -->
    <section class="blog-section section-padding" id="most-rating">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg- col-12">
                    <div class="wpo-section-title s2">
                        <span>// Wisata Terbaik</span>
                        <h2>Jelajahi Destinasi Wisata<br> Terindah di Malang Raya</h2>
                    </div>
                </div>
                <div class="col-lg-10 col-12">
                    <div class="authorlist-wrap">
                        <div class="row">
                            @foreach ($topRatedDestinations as $destination)
                                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                                    <div class="auth-card">
                                        <div class="shape">
                                            <svg viewBox="0 0 250 246" fill="none">
                                                <path
                                                    d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                            </svg>
                                        </div>
                                        <div class="image">
                                            <div class="img"
                                                style="background-image: url('{{ $destination->gallery[0]->image_url ?? 'https://picsum.photos/200.webp' }}');">
                                            </div>
                                            {{--  <img src="{{ $destination->gallery[0]->image_url ?? 'https://picsum.photos/200.webp' }}"
                                                alt="{{ $destination->name }}">  --}}
                                        </div>
                                        <div class="content">
                                            <div class="rating">
                                                Rating: {{ number_format($destination->reviews_avg_rating, 1) }} (<img
                                                    src="{{ asset('assets/user/images/authorlist/star.svg') }}"
                                                    alt="">)
                                            </div>
                                            <h2>{{ $destination->name }}</h2>
                                            <h4>Rp {{ number_format($destination->price, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
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
                    {{--  <div class="wpo-section-title s2 wow fadeInLeftSlow" data-wow-duration="1700ms">  --}}
                    <div class="wpo-section-title s2">
                        <p style="color: #FFFFFF;">// Wisata Terbaik</p>
                        <h2 style="margin-top: 20px; color: #FFFFFF;">Beritahu Kami!</h2>
                        <p style="margin: 20px 0 0 0; color: #FFFFFF;">Ayo, jadi salah satu yang
                            pertama mengungkap
                            keajaiban tersembunyi ini!
                            Rencanakan perjalanan Anda sekarang dan ciptakan pengalaman yang tak terlupakan.</p>
                    </div>
                </div>
                <div class="offset-xl-2 col-xl-6 col-12">
                    <div class="container-form">
                        <form action="{{ route('home.recommendation.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 mt-4">
                                    <div class="mb-4">
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                            id="name" name="name" placeholder="Nama Wisata"
                                            value="{{ old('name') }}">
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <input type="text" class="form-control @error('address') is-invalid @enderror"
                                            id="address" name="address" placeholder="Alamat"
                                            value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-3">
                                        <textarea class="form-control" id="description @error('description') is-invalid @enderror" name="description"
                                            rows="3" placeholder="Deskripsi">{{ old('description') }}</textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="mb-4">
                                        <label style="color: black" for="image_url" class="form-label">Upload Gambar
                                            Wisata</label>
                                        <input class="form-control @error('image_url') is-invalid @enderror"
                                            type="file" id="image_url" name="image_url[]" multiple>
                                        @error('image_url')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Bagian Map -->
                                <div class="col-md-6 d-flex align-items-center">
                                    <div class="preview-box text-center w-100">
                                        <span class="text-muted">Tentukan Lokasi Wisata</span>
                                        <div id="map"
                                            class="border @error('latitude') border-danger @enderror @error('longitude') border-danger @enderror"
                                            style="height: 250px; width: 100%; border-radius: 7px;"></div>

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
                            @auth
                                <button type="submit" class="btn btn-primary">Kirim</button>
                            @endauth

                            @guest
                                <a href="{{ route('login') }}">
                                    <button type="button" class="btn btn-primary">Kirim</button>
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
