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
    <section class="verifikasi-section section-padding" style="margin-top: -150px">
        <div class="container mt-5 d-flex">
            <div class="row">
                <!-- Sidebar Filter -->
                <div class="sidebar-filter col-md-3 col-12" style="height: 140px">
                    <form action="{{ route('article.browse') }}" method="GET">
                        <input type="text" name="search" class="search-box" placeholder="{{ __('main.cari_artikel') }}"
                            value="{{ request('search') }}">
                        {{--  <h4>{{ __('main.filter') }}</h4>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">{{ __('main.jenis_tempat') }}</label>
                            <div class="checkbox-group">
                                <label for="restoran">
                                    <input type="checkbox" name="jenis_tempat[]" value="Restoran" id="restoran"
                                        {{ in_array('Restoran', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    {{ __('main.restoran') }}
                                </label>
                                <label for="penginapan">
                                    <input type="checkbox" name="jenis_tempat[]" value="Penginapan" id="penginapan"
                                        {{ in_array('Penginapan', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    {{ __('main.penginapan') }}
                                </label>
                                <label for="other">
                                    <input type="checkbox" name="jenis_tempat[]" value="Other" id="other"
                                        {{ in_array('Other', request('jenis_tempat', [])) ? 'checked' : '' }}>
                                    {{ __('main.lainnya') }}
                                </label>
                            </div>
                        </div>  --}}
                        <button type="submit" class="btn btn-primary">{{ __('main.cari') }}</button>
                    </form>
                </div>

                <!-- Place Content -->
                <div class="container-form col-md-9 col-12">
                    @if ($articles->isEmpty())
                        <div class="col-12 text-center">
                            <p class="text-muted">{{ __('main.tidak_ada_artikel') }}</p>
                            <p class="text-muted">{{ __('main.coba_kata_kunci_lain') }}</p>
                        </div>
                    @else
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ __('main.urutkan') }}
                                @php
                                    $sort = request('sort', 'populer');
                                    switch ($sort) {
                                        case 'populer':
                                            echo __('main.paling_populer');
                                            break;
                                        case 'terbaru':
                                            echo __('main.terbaru');
                                            break;
                                        case 'like_terbanyak':
                                            echo __('main.like_terbanyak');
                                            break;
                                        case 'komen_terbanyak':
                                            echo __('main.komen_terbanyak');
                                            break;
                                        default:
                                            echo __('main.paling_populer');
                                    }
                                @endphp
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item"
                                        href="{{ route('article.browse', array_merge(request()->all(), ['sort' => 'populer'])) }}">
                                        {{ __('main.paling_populer') }}</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('article.browse', array_merge(request()->all(), ['sort' => 'terbaru'])) }}">
                                        {{ __('main.terbaru') }}</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('article.browse', array_merge(request()->all(), ['sort' => 'like_terbanyak'])) }}">
                                        {{ __('main.like_terbanyak') }}</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('article.browse', array_merge(request()->all(), ['sort' => 'komen_terbanyak'])) }}">
                                        {{ __('main.komen_terbanyak') }}</a></li>
                            </ul>
                        </div>


                        <div class="gallery-container gallery-fancybox masonry-gallery row">
                            @foreach ($articles as $article)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 custom-grid" data-wow-duration="2000ms">
                                    <a href="{{ route('article.show', $article->slug) }}" class="text-decoration-none">
                                        <div class="featured-card d-flex flex-column h-100" style="min-height: 500px;">
                                            <div class="image">
                                                <div class="img lazy-bg"
                                                    data-bg="{{ $article->thumbnail ?? 'assets/images/default.png' }}">
                                                </div>
                                            </div>
                                            <div class="content flex-grow-1 d-flex flex-column">
                                                <h2>
                                                    <a
                                                        href="{{ route('article.show', $article->slug) }}">{{ $article->title }}</a>
                                                </h2>
                                                <div class="description flex-grow-1 d-flex align-items-start"
                                                    style="min-height: 50px;">
                                                    <span class="overflow-hidden"
                                                        style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                        {!! Str::limit(
                                                            preg_replace('/<figure[^>]*>.*?<\/figure>/', '', strip_tags(html_entity_decode($article->content))),
                                                            100,
                                                        ) ?? __('main.deskripsi_tidak_tersedia') !!}
                                                    </span>
                                                </div>
                                                <div class="top-content mt-auto">
                                                    <ul>
                                                        <li>
                                                            <span>{{ formatLikes($article->views->count()) }}</span>
                                                            <span class="date">{{ __('main.lihat') }}</span>
                                                        </li>
                                                        <li>
                                                            <span>{{ formatLikes($article->likes->count()) }}</span>
                                                            <span class="date">{{ __('main.suka') }}</span>
                                                        </li>
                                                        <li>
                                                            <span>{{ formatLikes($article->comments->count()) }}</span>
                                                            <span class="date">{{ __('main.komentar') }}</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $articles->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection
