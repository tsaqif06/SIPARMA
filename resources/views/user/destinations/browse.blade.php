@extends('user.layouts.app')

@section('content')
    <section class="verifikasi-section section-padding" style="margin-top: -150px">
        <div class="container mt-5 d-flex">
            <!-- Sidebar Filter -->
            <div class="row">
                <div class="sidebar-filter col-md-3 col-12">
                    <form action="{{ route('destination.browse') }}" method="GET">
                        <input type="text" name="search" class="search-box" placeholder="{{ __('main.cari_wisata') }}"
                            value="{{ request('search') }}">
                        <h4>{{ __('main.filter') }}</h4>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">{{ __('main.jenis_wisata') }}</label>
                            <div class="checkbox-group">
                                <label for="wahana">
                                    <input type="checkbox" name="jenis_wisata[]" value="Wahana" id="wahana"
                                        {{ in_array('Wahana', request('jenis_wisata', [])) ? 'checked' : '' }}>
                                    {{ __('main.wahana') }}
                                </label>
                                <label for="alam">
                                    <input type="checkbox" name="jenis_wisata[]" value="Alam" id="alam"
                                        {{ in_array('Alam', request('jenis_wisata', [])) ? 'checked' : '' }}>
                                    {{ __('main.alam') }}
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">{{ __('main.harga') }}</label>
                            <input type="number" name="harga_min" class="price-input"
                                placeholder="{{ __('main.harga_minimum') }}" value="{{ request('harga_min') }}">
                            <input type="number" name="harga_max" class="price-input"
                                placeholder="{{ __('main.harga_maximum') }}" value="{{ request('harga_max') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">{{ __('main.terapkan_filter') }}</button>
                    </form>
                </div>

                <!-- Destination Content -->
                <div class="container-form col-md-9 col-12">
                    @if ($destinations->isEmpty())
                        <div class="col-12 text-center">
                            <p class="text-muted">{{ __('main.tidak_ada_wisata') }}</p>
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
                                        case 'harga_tertinggi':
                                            echo __('main.harga_tertinggi');
                                            break;
                                        case 'harga_terendah':
                                            echo __('main.harga_terendah');
                                            break;
                                        case 'rating_tertinggi':
                                            echo __('main.rating_tertinggi');
                                            break;
                                        default:
                                            echo __('main.paling_populer');
                                    }
                                @endphp
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'populer'])) }}">{{ __('main.paling_populer') }}</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'harga_tertinggi'])) }}">{{ __('main.harga_tertinggi') }}</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'harga_terendah'])) }}">{{ __('main.harga_terendah') }}</a>
                                </li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'rating_tertinggi'])) }}">{{ __('main.rating_tertinggi') }}</a>
                                </li>
                            </ul>
                        </div>

                        <div class="gallery-container gallery-fancybox masonry-gallery row">
                            @foreach ($destinations as $destination)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 custom-grid" data-wow-duration="2000ms">
                                    <div class="featured-card d-flex flex-column h-100" style="min-height: 500px;">
                                        <div class="image">
                                            <div class="img lazy-bg"
                                                data-bg="{{ $destination->gallery[0]->image_url ?? asset('assets/images/default.png') }}">
                                            </div>
                                        </div>
                                        <div class="content flex-grow-1 d-flex flex-column">
                                            <h2>
                                                <a
                                                    href="{{ route('destination.show', $destination->slug) }}">{{ $destination->getTranslatedName() }}</a>
                                            </h2>
                                            <div class="description flex-grow-1 d-flex align-items-start"
                                                style="min-height: 40px;">
                                                <span class="overflow-hidden"
                                                    style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical;">
                                                    {{ $destination->getTranslatedDescription() ?? __('main.deskripsi_tidak_tersedia') }}
                                                </span>
                                            </div>
                                            <div class="top-content mt-auto">
                                                <ul>
                                                    <li>
                                                        <span>{{ $destination->price ? number_format($destination->price, 0, ',', '.') : 'N/A' }}</span>
                                                        <span class="date">{{ __('main.harga') }}</span>
                                                    </li>
                                                    <li>
                                                        <span>{{ number_format($destination->reviews->count() ?? '0', 0, ',', '.') }}</span>
                                                        <span class="date">{{ __('main.ulasan') }}</span>
                                                    </li>
                                                    <li>
                                                        <span>{{ number_format($destination->reviews_avg_rating, 1) ?? '0.0' }}</span>
                                                        <span class="date">{{ __('main.rating') }}</span>
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
                    {{ $destinations->links('vendor.pagination.bootstrap-5') }}
                </div>
            </div>
        </div>
    </section>
@endsection
