@extends('user.layouts.app')

@php
    $script = '<script>
        //---------------------------
        let visibleRides = 2;
        const ridesToShow = 3;

        $(".hidden-ride").hide();

        $(".load-more").on("click", function(e) {
            e.preventDefault();
            $(".hidden-ride").slice(visibleRides - 2, visibleRides - 2 + ridesToShow).show();
            visibleRides += ridesToShow;

            if (visibleRides >= $(".hidden-ride").length + 2) {
                $(this).hide();
            }
        });

        //---------------------------
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });

        function confirmDelete(userId) {
            Swal.fire({
                title: ' . json_encode(__("main.apakah_anda_yakin")) . ',
                text: ' . json_encode(__("main.data_akan_dihapus")) . ',
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: ' . json_encode(__("main.hapus")) . ',
                cancelButtonText: ' . json_encode(__("main.batal")) . ',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#deleteForm" + userId).submit();
                }
            });
        }

        //---------------------------
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

        L.marker([lat, lng], {
            icon: defaultIcon
        }).addTo(map);

        document.getElementById("showRoute").addEventListener("click", function() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userLatLng = [position.coords.latitude, position.coords.longitude];

                    if (window.userMarker) {
                        map.removeLayer(window.userMarker);
                    }

                    window.userMarker = L.marker(userLatLng, {
                            icon: defaultIcon
                        })
                        .addTo(map)
                        .bindPopup(' . json_encode(__("main.lokasi_anda")) . ')
                        .openPopup();

                    map.setView(userLatLng, 14);

                    var tujuanLatLng = [lat, lng];

                    if (window.routingControl) {
                        map.removeControl(window.routingControl);
                    }

                    window.routingControl = L.Routing.control({
                        waypoints: [L.latLng(userLatLng), L.latLng(tujuanLatLng)],
                        routeWhileDragging: true,
                        show: false
                    }).addTo(map);
                },
                function() {
                    alert(' . json_encode(__("main.gagal_mendapatkan_lokasi")) . ');
                }
            );
        });
    </script>';
@endphp

@section('content')

    <!-- wpo-room-area-start -->
    <div class="wpo-room-area-s2 section-padding pb-0 position-relative" style="margin-top: -120px">
        @php
            $galleryChunks = $destination->gallery->chunk(3);
        @endphp

        @if ($galleryChunks->count() > 1)
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators"
                data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        @endif


        <div class="container">
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach ($galleryChunks as $key => $chunk)
                        @php
                            // Reset index jadi mulai dari 0
                            $chunk = $chunk->values();
                            $mainImage = $chunk[0] ?? null;
                            $sideImages = $chunk->slice(1);
                        @endphp

                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row">
                                <div class="col-md-8 mb-sm-2">
                                    @if ($mainImage)
                                        <a href="{{ asset($mainImage->image_url ?? 'assets/images/default.png') }}">
                                            <img src="{{ asset($mainImage->image_url ?? 'assets/images/default.png') }}"
                                                class="d-block w-100 img-clickable" alt="{{ $destination->name }}">
                                        </a>
                                    @endif
                                </div>
                                <div class="col-md-4 d-flex flex-column">
                                    @foreach ($sideImages as $item)
                                        <a href="{{ asset($item->image_url ?? 'assets/images/default.png') }}">
                                            <img src="{{ asset($item->image_url ?? 'assets/images/default.png') }}"
                                                class="mb-2 w-100 img-clickable" loading="lazy" alt="{{ $destination->name }}">
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- .room-area-start -->

    @php
        $location = json_decode($destination->location);
    @endphp

    <!--Start Room-details area-->
    <div class="room-details-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="room-description">
                        <div class="room-title">
                            <h2>{{ $destination->getTranslatedName() }}</h2>
                        </div>
                        <p class="p-wrap">
                            {{ $destination->getTranslatedDescription() ?? __('main.deskripsi_tidak_tersedia') }}</p>
                        <p class="p-wrap">{{ $location->address }}</p>
                        <div class="room-title">
                            <h2>{{ __('main.fasilitas') }}</h2>
                        </div>
                        <div class="fasilitas-list">
                            @if ($destination->facilities->isEmpty())
                                <p>{{ __('main.belum_ada_fasilitas') }}</p>
                            @else
                                @foreach ($destination->facilities as $facility)
                                    <span class="fasilitas-item">{{ $facility->getTranslatedName() }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="pricing-area">
                        <div class="room-title">
                            <h2>{{ __('main.tiket') }}</h2>
                        </div>
                        <div class="ticket-card">
                            <div class="img lazy-bg"
                                data-bg="{{ $destination->gallery[0]->image_url ? '../' . $destination->gallery[0]->image_url : asset('assets/images/default.png') }}">
                            </div>
                            <div class="ticket-info">
                                <div class="ticket-title">{{ __('main.tiket') }} {{ $destination->getTranslatedName() }}
                                </div>
                                <div class="ticket-desc">{{ __('main.tiket_wisata') }}</div>
                            </div>
                            <div class="ticket-price-button">
                                @php
                                    $diskonPersen = $destination->promos[0]->discount ?? 0;
                                    $hargaDiskon = $destination->price - ($destination->price * $diskonPersen) / 100;
                                @endphp

                                @if ($diskonPersen > 0)
                                    <span class="price-old">IDR
                                        {{ number_format($destination->price, 0, ',', '.') }}</span>
                                @endif
                                <span class="ticket-price">IDR {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                <a href="{{ route('destination.checkout', ['slug' => $destination->slug, 'type' => 'destination']) }}"
                                    class="buy-button">{{ __('main.beli_tiket') }}</a>
                            </div>
                        </div>

                        @foreach ($destination->rides->take(2) as $ride)
                            <div class="ticket-card">
                                <div class="img lazy-bg"
                                    data-bg="{{ !empty($ride->gallery) && isset($ride->gallery[0]) ? '../' . $ride->gallery[0]->image_url : asset('assets/images/default.png') }}">
                                </div>
                                <div class="ticket-info">
                                    <div class="ticket-title">{{ __('main.tiket_wahana') }} - {{ $ride->name }}</div>
                                    <div class="ticket-desc">{{ __('main.tiket_wahana') }}</div>
                                </div>
                                <div class="ticket-price-button">
                                    <span class="ticket-price">IDR {{ number_format($ride->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('destination.checkout', ['slug' => $ride->slug, 'type' => 'ride']) }}"
                                        class="buy-button">{{ __('main.beli_tiket') }}</a>
                                </div>
                            </div>
                        @endforeach

                        @if ($destination->rides->count() > 2)
                            <div id="more-rides">
                                @foreach ($destination->rides->slice(2) as $ride)
                                    <div class="ticket-card hidden-ride">
                                        <div class="img lazy-bg"
                                            data-bg="{{ !empty($ride->gallery) && isset($ride->gallery[0]) ? '../' . $ride->gallery[0]->image_url : asset('assets/images/default.png') }}">
                                        </div>
                                        <div class="ticket-info">
                                            <div class="ticket-title">{{ __('main.tiket_wahana') }} - {{ $ride->name }}
                                            </div>
                                            <div class="ticket-desc">{{ __('main.tiket_wahana') }}</div>
                                        </div>
                                        <div class="ticket-price-button">
                                            <span class="ticket-price">IDR
                                                {{ number_format($ride->price, 0, ',', '.') }}</span>
                                            <a href="{{ route('destination.checkout', ['slug' => $ride->slug, 'type' => 'ride']) }}"
                                                class="buy-button">{{ __('main.beli_tiket') }}t</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="#" class="load-more">{{ __('main.lihat_lebih_banyak') }}</a>
                        @endif
                    </div>

                    @if ($destination->bundles->count() > 0)
                        <div class="pricing-area mt-5">
                            <div class="room-title">
                                <h2>{{ __('main.bundling_tiket') }}</h2>
                            </div>
                            @foreach ($destination->bundles as $bundle)
                                <div class="ticket-card">
                                    <div class="img lazy-bg"
                                        data-bg="{{ !empty($bundle->gallery) && isset($bundle->gallery[0]) ? '../' . $bundle->gallery[0]->image_url : asset('assets/images/default.png') }}">
                                    </div>
                                    <div class="ticket-info">
                                        <div class="ticket-title">{{ __('main.tiket_bundle') }} - {{ $bundle->name }}
                                        </div>
                                        <div class="ticket-desc">{{ __('main.isi_item_bundle') }}:
                                            <ul class="list-unstyled mb-0">
                                                @foreach ($bundle->items as $item)
                                                    @php
                                                        $quantities = collect(json_decode($item->quantity, true))
                                                            ->map(function ($qty, $key) {
                                                                $label =
                                                                    $key === 'adults'
                                                                        ? __('main.dewasa')
                                                                        : __('main.anakanak');
                                                                return $label . ': ' . $qty;
                                                            })
                                                            ->implode(', ');
                                                    @endphp
                                                    <li>{{ optional($item->item)->name }}
                                                        <small class="text-muted">({{ $quantities }})</small>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>

                                    @php
                                        $discountedPrice = $bundle->discount ?? 0;
                                        $hargaDiskon =
                                            $bundle->total_price - ($bundle->total_price * $discountedPrice) / 100;
                                    @endphp

                                    <div class="ticket-price-button">
                                        @if ($discountedPrice > 0)
                                            <span class="price-old">IDR
                                                {{ number_format($bundle->total_price, 0, ',', '.') }}</span>
                                        @endif
                                        <span class="ticket-price">IDR
                                            {{ number_format($hargaDiskon, 0, ',', '.') }}</span>
                                        <a href="{{ route('destination.checkout', ['slug' => $bundle->id, 'type' => 'bundle']) }}"
                                            class="buy-button">{{ __('main.beli_tiket') }}</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    <div class="room-review">
                        <div class="room-title">
                            <h2>{{ __('main.ulasan') }}</h2>
                        </div>

                        @php
                            $userComments = $reviews->where('user_id', auth()->id());
                            $otherComments = $reviews->where('user_id', '!=', auth()->id());
                            $sortedComments = $userComments->merge($otherComments);
                        @endphp

                        @if ($sortedComments->isEmpty())
                            <p>{{ __('main.belum_ada_ulasan') }}</p>
                        @else
                            @foreach ($sortedComments as $review)
                                <div class="review-item">
                                    <div class="review-img">
                                        <div class="img lazy-bg"
                                            data-bg="{{ asset(
                                                file_exists(public_path($review->user->profile_picture)) && $review->user->profile_picture
                                                    ? $review->user->profile_picture
                                                    : 'assets/images/default-avatar.jpg',
                                            ) }}">
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <div class="r-title">
                                            <h2>{{ $review->user->name }}</h2>
                                            <span class="ms-2">{{ $review->created_at->format('d M Y') }}</span>
                                            <ul style="display: flex;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li>
                                                        <iconify-icon
                                                            icon="{{ $i <= $review->rating ? 'material-symbols:star' : 'material-symbols:star-outline' }}"
                                                            class="menu-icon" style="font-size: 24px; color: gold;">
                                                        </iconify-icon>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <p>{{ $review->comment }}</p>

                                        <div class="d-flex gap-2">
                                            @if (auth()->id() == $review->user_id)
                                                <form action="{{ route('reviews.destroy', $review->id) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1"
                                                        onclick="return confirm('{{ __('main.hapus_komen_ini') }}?')">
                                                        <iconify-icon icon="solar:trash-bin-trash-linear"
                                                            style="font-size: 18px;"></iconify-icon>
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="pagination mt-4">
                                {{ $reviews->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    </div>

                    @php
                        $userReview = \App\Models\Review::where('user_id', auth()->id())
                            ->when(isset($destination), function ($query) use ($destination) {
                                return $query->where('destination_id', $destination->id);
                            })
                            ->first();
                    @endphp

                    @if (!$userReview)
                        <div class="add-review">
                            <div class="room-title">
                                <h2>{{ __('main.berikan_ulasan') }}</h2>
                            </div>

                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                                <div class="wpo-blog-single-section review-form">
                                    <div class="give-rat-sec">
                                        <div class="give-rating">
                                            <label>
                                                <input type="radio" name="rating" value="1"
                                                    {{ old('rating') == 1 ? 'checked' : '' }} required />
                                                <span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="2"
                                                    {{ old('rating') == 2 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="3"
                                                    {{ old('rating') == 3 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="4"
                                                    {{ old('rating') == 4 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span><span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="5"
                                                    {{ old('rating') == 5 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span>
                                            </label>
                                            @error('rating')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="review-add">
                                        <div class="comment-respond">
                                            <div id="commentform" class="comment-form">
                                                <div class="form">
                                                    <textarea id="comment" name="comment" placeholder="{{ __('main.ketik_ulasan') }}" required>{{ old('comment') }}</textarea>
                                                    @error('comment')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-submit">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="font-size: 16px;">{{ __('main.kirim_ulasan') }}</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-12">
                    <div class="blog-sidebar room-sidebar">
                        <div class="preview-box text-center w-100">
                            <div id="map" class="border" style="height: 500px; width: 100%; border-radius: 7px;">
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ $location->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $location->longitude }}">
                            <button id="showRoute"
                                class="btn btn-primary mt-3">{{ __('main.tunjukkan_navigasi') }}</button>
                        </div>
                        <div class="wpo-contact-widget widget mt-5">
                            <h4>{{ __('main.mengalami_masalah') }}</h4>
                            <p>{{ __('main.laporkan_tempat_ini') }}</p>
                            <a href="#" data-bs-toggle="modal"
                                data-bs-target="#laporModal">{{ __('main.laporkan') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="laporModal" tabindex="-1" aria-labelledby="laporModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="laporModalLabel">{{ __('main.laporkan_masalah') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="destination_id" value="{{ $destination->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaint_text" class="form-label">{{ __('main.isi_laporan') }}</label>
                            <textarea class="form-control" name="complaint_text" rows="4" required
                                placeholder="{{ __('main.tuliskan_laporan') }}"></textarea>
                            @error('complaint_text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ __('main.batal') }}</button>
                        <button type="submit" class="btn btn-primary">{{ __('main.kirim_laporan') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <section class="blog-section section-padding">
        <div class="container">
            <div class="room-title">
                <h2>{{ __('main.tempat_terdekat') }}</h2>
            </div>
            <div class="authorlist-wrap">
                <div class="row">
                    @foreach ($destination->places as $place)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                            <a href="{{ route('place.show', $place->slug) }}" class="text-decoration-none">
                                <div class="auth-card">
                                    <div class="shape">
                                        <svg viewBox="0 0 250 246" fill="none">
                                            <path
                                                d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                        </svg>
                                    </div>
                                    <div class="image">
                                        <div class="img lazy-bg"
                                            data-bg="{{ !empty($place->gallery) && isset($place->gallery[0]) ? '../' . $place->gallery[0]->image_url : asset('assets/images/default.png') }}">
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="rating">
                                            Rating: {{ number_format($place->reviews_avg_rating, 1) }} (<img
                                                src="{{ asset('assets/user/images/authorlist/star.svg') }}"
                                                alt="" loading="lazy">)
                                        </div>
                                        <h2>{{ $place->name }}</h2>
                                        <h4>{{ $place->getTranslatedType() }}</h4>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!--End Room-details area-->
@endsection
