@extends('user.layouts.app')

@php
    $script = '<script>
        //---------------------------
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });

        function confirmDelete(userId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#deleteForm" + userId).submit();
                }
            });
        }

        //-----------------------
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

        // Fungsi untuk menampilkan navigasi rute
        document.getElementById("showRoute").addEventListener("click", function() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    var userLatLng = [position.coords.latitude, position.coords.longitude];

                    // Hapus marker lama kalau ada
                    if (window.userMarker) {
                        map.removeLayer(window.userMarker);
                    }

                    // Tambahkan marker untuk lokasi pengguna
                    window.userMarker = L.marker(userLatLng, {
                            icon: defaultIcon
                        })
                        .addTo(map)
                        .bindPopup("Lokasi Anda")
                        .openPopup();

                    // Zoom ke lokasi pengguna
                    map.setView(userLatLng, 14);

                    // Lokasi tujuan
                    var tujuanLatLng = [lat, lng];

                    // Hapus rute lama kalau ada
                    if (window.routingControl) {
                        map.removeControl(window.routingControl);
                    }

                    // Tambahkan navigasi rute dari lokasi pengguna ke tujuan
                    window.routingControl = L.Routing.control({
                        waypoints: [L.latLng(userLatLng), L.latLng(tujuanLatLng)],
                        routeWhileDragging: true,
                        show: false // Menyembunyikan panel rute
                    }).addTo(map);
                },
                function() {
                    alert("Gagal mendapatkan lokasi Anda. Pastikan GPS aktif.");
                }
            );
        });
    </script>';
@endphp

@section('content')
    <!-- wpo-room-area-start -->
    <div class="wpo-room-area-s2 section-padding pb-0 position-relative" style="margin-top: -120px">
        @php
            $galleryChunks = $place->gallery->chunk(3);
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
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row">
                                <div class="col-md-8 mb-sm-2">
                                    <img src="{{ asset($chunk[0]->image_url ?? 'assets/images/default.png') }}"
                                        class="d-block w-100 img-clickable" alt="{{ $place->name }}">
                                </div>
                                <div class="col-md-4 d-flex flex-column">
                                    <img src="{{ asset($chunk[1]->image_url ?? ($chunk[0]->image_url ?? 'assets/images/default.png')) }}"
                                        class="mb-2 w-100 img-clickable" loading="lazy" alt="{{ $place->name }}">
                                    <img src="{{ asset($chunk[2]->image_url ?? ($chunk[0]->image_url ?? 'assets/images/default.png')) }}"
                                        class="w-100 img-clickable" loading="lazy" alt="{{ $place->name }}">
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
        $location = json_decode($place->location);
    @endphp

    <!--Start Room-details area-->
    <div class="room-details-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="room-description">
                        <div class="room-title">
                            <h2>{{ $place->name }}</h2>
                        </div>
                        <p class="p-wrap">{{ $place->description }}</p>
                        <p class="p-wrap">{{ $location->address }}</p>
                        <div class="room-title">
                            <h2>Fasilitas</h2>
                        </div>
                        <div class="fasilitas-list">
                            @if ($place->facilities->isEmpty())
                                <p>Belum ada fasilitas yang tersedia.</p>
                            @else
                                @foreach ($place->facilities as $facility)
                                    <span class="fasilitas-item">{{ ucfirst($facility->name) }}</span>
                                @endforeach
                            @endif
                        </div>
                    </div>

                    <div class="room-review">
                        <div class="room-title">
                            <h2>Ulasan</h2>
                        </div>

                        @if ($reviews->isEmpty())
                            <p>Belum ada ulasan.</p>
                        @else
                            @foreach ($reviews as $review)
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
                            ->when(isset($place), function ($query) use ($place) {
                                return $query->where('place_id', $place->id);
                            })
                            ->first();
                    @endphp

                    <div class="add-review">
                        @if ($userReview)
                            <div class="room-title">
                                <h2>Ulasan Anda</h2>
                            </div>
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
                                        <h2>{{ $userReview->user->name }}</h2>
                                        <span class="ms-2">{{ $userReview->created_at->format('d M Y') }}</span>
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
                                    <p>{{ $userReview->comment }}</p>
                                </div>

                                <div class="review-actions">
                                    <form id="deleteForm{{ $userReview->id }}"
                                        action="{{ route('reviews.destroy', $userReview->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete({{ $userReview->id }})"><iconify-icon icon="mdi:trash"
                                                width="24" height="24"></iconify-icon></button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="room-title">
                                <h2>Berikan Ulasan</h2>
                            </div>

                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="place_id" value="{{ $place->id }}">
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
                                                    <textarea id="comment" name="comment" placeholder="Ketik Ulasan" required>{{ old('comment') }}</textarea>
                                                    @error('comment')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-submit">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="font-size: 16px;">Kirim
                                                        Ulasan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
                <div class="col-lg-4 col-12">
                    <div class="blog-sidebar room-sidebar">
                        <div class="preview-box text-center w-100">
                            <div id="map" class="border" style="height: 500px; width: 100%; border-radius: 7px;">
                            </div>
                            <input type="hidden" name="latitude" id="latitude" value="{{ $location->latitude }}">
                            <input type="hidden" name="longitude" id="longitude" value="{{ $location->longitude }}">
                            <button id="showRoute" class="btn btn-primary mt-3">Tunjukkan Navigasi dari Lokasi
                                Saya</button>
                        </div>
                        <div class="wpo-contact-widget widget mt-5">
                            <h4>Mengalami Masalah?</h4>
                            <p>Laporkan tempat ini jika tempat ini mengalami masalah</p>
                            <a href="#" data-bs-toggle="modal" data-bs-target="#laporModal">Laporkan</a>
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
                    <h5 class="modal-title" id="laporModalLabel">Laporkan Masalah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="place_id" value="{{ $place->id }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaint_text" class="form-label">Isi Laporan</label>
                            <textarea class="form-control" name="complaint_text" rows="4" required placeholder="Tuliskan laporan Anda..."></textarea>
                            @error('complaint_text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Room-details area-->
@endsection
