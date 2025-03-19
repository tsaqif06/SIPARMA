@extends('user.layouts.app')

@php
    function formatLikes($num)
    {
        if ($num >= 1000000) {
            return number_format($num / 1000000, 1, ',', '.') . 'jt';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1, ',', '.') . 'k';
        }
        return $num;
    }
@endphp

@section('content')
    <div class="container">
        <div class="hero-article-browse lazy-bg overlay-dark d-flex justify-content-center align-items-center"
            data-bg="{{ asset('assets/images/hero-article.jpg') }}" style="height: 250px;">
            <div class="text-center">
                <h1 class="text-white mb-4">Cari Artikel</h1>
                <div class="search-bar">
                    <form action="{{ route('article.browse') }}" method="GET" class="d-flex justify-content-center">
                        <input type="text" class="search-box rounded-pill w-100 me-2"
                            placeholder="Masukkan kata kunci...">
                    </form>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            @foreach ($articles as $article)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card" style="min-height: 450px;">
                        <div class="article-thumbnail-browse">
                            <div class="img lazy-bg" data-bg="{{ $article->thumbnail ?? 'assets/images/default.png' }}">
                            </div>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">
                                {{ $article->title }} <i class="fas fa-arrow-right arrow-icon"></i>
                            </h5>
                            <p class="card-text">
                                {!! Str::limit(
                                    preg_replace('/<figure[^>]*>.*?<\/figure>/', '', strip_tags(html_entity_decode($article->content))),
                                    100,
                                ) !!}
                            </p>

                            <a href="{{ route('article.show', ['slug' => $article->slug]) }}" class="stretched-link"></a>
                        </div>

                        <div class="card-footer">
                            <div class="d-flex align-items-center gap-3">
                                <!-- View -->
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:eye-linear"
                                        style="color: black; font-size: 25px"></iconify-icon>
                                    <span class="text-muted" style="font-size: 14px;">
                                        {{ formatLikes($article->views->count()) }}
                                    </span>
                                </div>

                                <!-- Like -->
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:heart-outline"
                                        style="color: black; font-size: 25px"></iconify-icon>
                                    <span class="text-muted" style="font-size: 14px;">
                                        {{ formatLikes($article->likes->count()) }}
                                    </span>
                                </div>

                                <!-- Comments -->
                                <div class="d-flex align-items-center gap-1">
                                    <iconify-icon icon="solar:chat-line-linear"
                                        style="color: black; font-size: 25px"></iconify-icon>
                                    <span class="text-muted" style="font-size: 14px;">
                                        {{ formatLikes($article->comments->count()) }}
                                    </span>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>
    </div>
    {{--  <section class="verifikasi-section section-padding" style="margin-top: -150px">
        <div class="container mt-5 d-flex">
            <!-- Sidebar Filter -->
            <div class="row">
                <div class="sidebar-filter col-md-3 col-12" style="height: 330px">
                    <form action="{{ route('place.browse') }}" method="GET">
                        <input type="text" name="search" class="search-box" placeholder="Cari tiket"
                            value="{{ request('search') }}">
                        <h4>Filter</h4>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">Jenis Tempat</label>
                            <div class="checkbox-group">
                                <label for="restoran">
                                    <input type="checkbox" name="jenis_tempat[]" value="Restoran" id="restoran"
                                        {{ in_array('Restoran', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    Restoran
                                </label>
                                <label for="penginapan">
                                    <input type="checkbox" name="jenis_tempat[]" value="Penginapan" id="penginapan"
                                        {{ in_array('Penginapan', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    Penginapan
                                </label>
                                <label for="other">
                                    <input type="checkbox" name="jenis_tempat[]" value="Other" id="other"
                                        {{ in_array('Other', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    Lainnya
                                </label>
                            </div>
                        </div>
                        <hr>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    </form>
                </div>

                <!-- Konten Wisata -->
                <div class="container-form col-md-9 col-12">
                    @if ($places->isEmpty())
                        <div class="col-12 text-center">
                            <p class="text-muted">Tidak ada tempat yang ditemukan.</p>
                            <p class="text-muted">Coba kata kunci lain atau hapus filter pencarian.</p>
                        </div>
                    @else
                        <div class="gallery-container gallery-fancybox masonry-gallery row">
                            @foreach ($places as $place)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 custom-grid" data-wow-duration="2000ms">
                                    <div class="featured-card">
                                        <div class="image">
                                            <div class="img lazy-bg"
                                                data-bg="{{ $place->gallery[0]->image_url ?? 'assets/images/default.png' }}">
                                            </div>
                                        </div>
                                        <div class="content">
                                            <h2>
                                                <a href="{{ route('place.show', $place->slug) }}">{{ $place->name }}</a>
                                            </h2>
                                            <span>{{ Str::limit($place->description ?? 'Deskripsi tidak tersedia.', 100, '...') }}</span>
                                            <div class="top-content">
                                                <ul>
                                                    <li>
                                                        <span>
                                                            {{ $place->price ? number_format($place->price, 0, ',', '.') : 'N/A' }}</span>
                                                        <span class="date">Harga</span>
                                                    </li>
                                                    <li>
                                                        <span>{{ number_format($place->reviews->count() ?? '0', 0, ',', '.') }}</span>
                                                        <span class="date">Ulasan</span>
                                                    </li>
                                                    <li>
                                                        <span>{{ number_format($place->reviews_avg_rating, 1) ?? '0.0' }}</span>
                                                        <span class="date">Rating</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $places->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>  --}}
@endsection
