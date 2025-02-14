@extends('user.layouts.app')

@php
    $script = '<script>
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
                        <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <img src="{{ asset($chunk[0]->image_url ?? 'assets/images/default.png') }}"
                                        class="d-block w-100" alt="{{ $destination->name }}">
                                </div>
                                <div class="col-md-4 d-flex flex-column">
                                    <img src="{{ asset($chunk[1]->image_url ?? ($chunk[0]->image_url ?? 'assets/images/default.png')) }}"
                                        class="mb-2 w-100" alt="{{ $destination->name }}">
                                    <img src="{{ asset($chunk[2]->image_url ?? ($chunk[0]->image_url ?? 'assets/images/default.png')) }}"
                                        class="w-100" alt="{{ $destination->name }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- .room-area-start -->

    <!--Start Room-details area-->
    <div class="room-details-area section-padding">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="room-description">
                        <div class="room-title">
                            <h2>{{ $destination->name }}</h2>
                        </div>
                        <p class="p-wrap">{{ $destination->description }}</p>
                        <div class="room-title">
                            <h2>Fasilitas</h2>
                        </div>
                        <div class="fasilitas-list">
                            @foreach ($destination->facilities as $facility)
                                <span class="fasilitas-item">{{ ucfirst($facility->name) }}</span>
                            @endforeach
                        </div>
                    </div>
                    <div class="pricing-area">
                        <div class="room-title">
                            <h2>Tiket</h2>
                        </div>
                        <div class="ticket-card">
                            <div class="img"
                                style="background-image: url('../{{ $destination->gallery[0]->image_url ?? 'assets/images/default.png' }}');">
                            </div>
                            <div class="ticket-info">
                                <div class="ticket-title">Tiket {{ $destination->name }}</div>
                                <div class="ticket-desc">Tiket Wisata</div>
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
                                    class="buy-button">Beli Tiket</a>
                            </div>
                        </div>
                        @foreach ($destination->rides->take(2) as $ride)
                            <div class="ticket-card">
                                <div class="img"
                                    style="background-image: url('../{{ $ride->gallery[0]->image_url ?? 'assets/images/default.png' }}');">
                                </div>
                                <div class="ticket-info">
                                    <div class="ticket-title">Tiket Wahana - {{ $ride->name }}</div>
                                    <div class="ticket-desc">Tiket Wahana</div>
                                </div>
                                <div class="ticket-price-button">
                                    <span class="ticket-price">IDR
                                        {{ number_format($ride->price, 0, ',', '.') }}</span>
                                    <a href="{{ route('destination.checkout', ['slug' => $ride->slug, 'type' => 'ride']) }}"
                                        class="buy-button">Beli Tiket</a>
                                </div>
                            </div>
                        @endforeach

                        @if ($destination->rides->count() > 2)
                            <div id="more-rides">
                                @foreach ($destination->rides->slice(2) as $ride)
                                    <div class="ticket-card hidden-ride">
                                        <div class="img"
                                            style="background-image: url('../{{ $ride->gallery[0]->image_url ?? 'assets/images/default.png' }}');">
                                        </div>
                                        <div class="ticket-info">
                                            <div class="ticket-title">Tiket Wahana - {{ $ride->name }}</div>
                                            <div class="ticket-desc">Tiket Wahana</div>
                                        </div>
                                        <div class="ticket-price-button">
                                            <span class="ticket-price">IDR
                                                {{ number_format($ride->price, 0, ',', '.') }}</span>
                                            <a href="{{ route('destination.checkout', ['slug' => $ride->slug, 'type' => 'ride']) }}"
                                                class="buy-button">Beli Tiket</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="#" class="load-more">Lihat Lebih Banyak</a>
                        @endif
                    </div>
                </div>
                <div class="room-review">
                    <div class="room-title">
                        <h2>Ulasan</h2>
                    </div>
                    <div class="review-item">
                        <div class="review-img">
                            <img src="assets/images/room/user1.png" alt="">
                        </div>
                        <div class="review-text">
                            <div class="r-title">
                                <h2>Sultan</h2>
                                <ul>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                </ul>
                            </div>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor
                                incididunt ut labore et dolore magna aliqua. Quis ipsum suspendisse ultrices
                                gravida. Risus commodo viverra maecenas accumsan lacus vel facilisis. </p>
                        </div>
                    </div>
                    <div class="review-item">
                        <div class="review-img">
                            <img src="assets/images/room/user1.png" alt="">
                        </div>
                        <div class="review-text">
                            <div class="r-title">
                                <h2>Manggali</h2>
                                <ul>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                    <li><i class="ti-star"></i></li>
                                </ul>
                            </div>
                            <p> Quis ipsum suspendisse ultrices gravida. Risus commodo viverra maecenas accumsan
                                lacus vel facilisis. </p>
                        </div>
                    </div>
                </div>
                <div class="add-review">
                    <div class="room-title">
                        <h2>Berikan Ulasan</h2>
                    </div>
                    <div class="wpo-blog-single-section review-form ">
                        <div class="give-rat-sec">
                            <div class="give-rating">
                                <label>
                                    <input type="radio" name="stars" value="1" />
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="2" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="3" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="4" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                                <label>
                                    <input type="radio" name="stars" value="5" />
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                    <span class="icon">★</span>
                                </label>
                            </div>
                        </div>
                        <div class="review-add">
                            <div class="comment-respond">
                                <form id="commentform" class="comment-form">
                                    <div class="form-textarea">
                                        <textarea id="comment" placeholder="Ketik Ulasan"></textarea>
                                    </div>
                                    <div class="form-submit">
                                        <button type="submit" class="btn btn-primary" style="font-size: 16px;">Kirim
                                            Ulasan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--Start Room area-->
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-12">
                <div class="blog-sidebar room-sidebar">
                    <div class="widget-maps">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d15806.180865305978!2d112.94269097883297!3d-7.9424720707752865!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2dd637aaab794a41%3A0xada40d36ecd2a5dd!2sGn.%20Bromo!5e0!3m2!1sid!2sus!4v1739258663084!5m2!1sid!2sus"
                            style="border:0; width: 100%; height: 100%; border-radius: 10px;" allowfullscreen=""
                            loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                    <div class="wpo-contact-widget widget">
                        <h4>Mengalami Masalah?</h4>
                        <p>Laporkan tempat ini jika tempat ini mengalami masalah</p>
                        <a href="#">Laporkan</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    </div>

    <section class="blog-section section-padding">
        <div class="container">
            <div class="room-title">
                <h2>Wisata Terdekat</h2>
            </div>
            <div class="authorlist-wrap wow fadeInRightSlow" data-wow-duration="1700ms">
                <div class="row">
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="auth-card">
                            <div class="shape">
                                <svg viewBox="0 0 250 246" fill="none">
                                    <path
                                        d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                </svg>
                            </div>
                            <div class="image">
                                <img src="assets/images/popular-places/place_2.png" alt="">
                            </div>
                            <div class="content">
                                <div class="rating">
                                    Rating: 4.8 (<img src="assets/images/authorlist/star.svg" alt="">)
                                </div>
                                <h2>Gunung Bromo</h2>
                                <h4>Penginapan</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="auth-card">
                            <div class="shape">
                                <svg viewBox="0 0 250 246" fill="none">
                                    <path
                                        d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                </svg>
                            </div>
                            <div class="image">
                                <img src="assets/images/popular-places/place_2.png" alt="">
                            </div>
                            <div class="content">
                                <div class="rating">
                                    Rating: 4.8 (<img src="assets/images/authorlist/star.svg" alt="">)
                                </div>
                                <h2>Gunung Bromo</h2>
                                <h4>Restoran</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="auth-card">
                            <div class="shape">
                                <svg viewBox="0 0 250 246" fill="none">
                                    <path
                                        d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                </svg>
                            </div>
                            <div class="image">
                                <img src="assets/images/popular-places/place_2.png" alt="">
                            </div>
                            <div class="content">
                                <div class="rating">
                                    Rating: 4.8 (<img src="assets/images/authorlist/star.svg" alt="">)
                                </div>
                                <h2>Gunung Bromo</h2>
                                <h4>Restoran</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                        <div class="auth-card">
                            <div class="shape">
                                <svg viewBox="0 0 250 246" fill="none">
                                    <path
                                        d="M0 90.5622C0 85.4392 3.25219 80.8812 8.09651 79.2148L234.097 1.47079C241.887 -1.2093 250 4.57911 250 12.8182V234C250 240.627 244.627 246 238 246H12C5.37258 246 0 240.627 0 234V90.5622Z" />
                                </svg>
                            </div>
                            <div class="image">
                                <img src="assets/images/popular-places/place_2.png" alt="">
                            </div>
                            <div class="content">
                                <div class="rating">
                                    Rating: 4.8 (<img src="assets/images/authorlist/star.svg" alt="">)
                                </div>
                                <h2>Gunung Bromo</h2>
                                <h4>Penginapan</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End Room-details area-->
@endsection
