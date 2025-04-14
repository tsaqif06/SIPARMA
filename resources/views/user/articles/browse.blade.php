@extends('user.layouts.app')

@php
    if (!function_exists('formatLikes')) {
        function formatLikes($num)
        {
            if ($num >= 1000000) {
                return number_format($num / 1000000, 1, ',', '.') . 'jt';
            } elseif ($num >= 1000) {
                return number_format($num / 1000, 1, ',', '.') . 'k';
            }
            return $num;
        }
    }
@endphp

@section('content')
    <!-- HERO SECTION (container-fluid style) -->
    <div class="hero-article-browse lazy-bg overlay-dark d-flex justify-content-center align-items-center"
        data-bg="{{ asset('assets/images/hero-article.jpg') }}" style="height: 350px;">
        <div class="text-center">
            <h1 class="text-white mb-4">{{ __('main.cari_artikel') }}</h1>
            <div class="search-bar">
                <form action="{{ route('article.browse') }}" method="GET" class="d-flex justify-content-center">
                    <input type="text" name="search" class="search-box rounded-pill w-100 me-2"
                        placeholder="{{ __('main.masukkan_kata_kunci') }}" value="{{ request('search') }}">
                </form>
            </div>
        </div>
    </div>

    <!-- CONTENT (inside container) -->
    <div class="container">
        <div class="article-section-title">
            <h2>{{ __('main.rekomendasi_untuk_anda') }}</h2>
        </div>
        <!-- ARTICLES LIST -->
        <div class="row mt-5">
            @forelse ($articles as $article)
                <div class="col-md-4 col-sm-6 mb-4">
                    <div class="card" style="min-height: 450px;">
                        <div class="article-thumbnail-browse">
                            <div class="img lazy-bg"
                                data-bg="{{ asset($article->thumbnail ?? 'assets/images/default.png') }}">
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
            @empty
                <div class="col-12 text-center">
                    <p class="text-muted">{{ __('main.tidak_ada_artikel') }}</p>
                    <p class="text-muted">{{ __('main.coba_kata_kunci_lain') }}</p>
                </div>
            @endforelse
        </div>

        <!-- PAGINATION -->
        <div class="d-flex justify-content-center mt-4">
            {{ $articles->links('vendor.pagination.bootstrap-5') }}
        </div>
    </div>
@endsection
