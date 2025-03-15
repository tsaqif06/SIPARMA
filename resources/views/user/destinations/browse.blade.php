@extends('user.layouts.app')

@section('content')
    <section class="verifikasi-section section-padding" style="margin-top: -150px">
        <div class="container mt-5 d-flex">
            <!-- Sidebar Filter -->
            <div class="row">
                <div class="sidebar-filter col-md-3 col-12">
                    <form action="{{ route('destination.browse') }}" method="GET">
                        <input type="text" name="search" class="search-box" placeholder="Cari tiket"
                            value="{{ request('search') }}">
                        <h4>Filter</h4>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">Jenis Wisata</label>
                            <div class="checkbox-group">
                                <label for="wahana">
                                    <input type="checkbox" name="jenis_wisata[]" value="Wahana" id="wahana"
                                        {{ in_array('Wahana', request('jenis_wisata', [])) ? 'checked' : '' }}>
                                    Wahana
                                </label>
                                <label for="alam">
                                    <input type="checkbox" name="jenis_wisata[]" value="Alam" id="alam"
                                        {{ in_array('Alam', request('jenis_wisata', [])) ? 'checked' : '' }}>
                                    Alam
                                </label>
                            </div>
                        </div>
                        <hr>
                        <div class="filter-section">
                            <label class="filter-title">Harga</label>
                            <input type="number" name="harga_min" class="price-input" placeholder="Harga Minimum Rp:"
                                value="{{ request('harga_min') }}">
                            <input type="number" name="harga_max" class="price-input" placeholder="Harga Maximum Rp:"
                                value="{{ request('harga_max') }}">
                        </div>
                        <button type="submit" class="btn btn-primary">Terapkan Filter</button>
                    </form>
                </div>

                <!-- Konten Wisata -->
                <div class="container-form col-md-9 col-12">
                    @if ($destinations->isEmpty())
                        <div class="col-12 text-center">
                            <p class="text-muted">Tidak ada wisata yang ditemukan.</p>
                            <p class="text-muted">Coba kata kunci lain atau hapus filter pencarian.</p>
                        </div>
                    @else
                        <div class="dropdown">
                            <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                Urutkan :
                                @php
                                    $sort = request('sort', 'populer');
                                    switch ($sort) {
                                        case 'populer':
                                            echo 'Paling Populer';
                                            break;
                                        case 'harga_tertinggi':
                                            echo 'Harga Tertinggi';
                                            break;
                                        case 'harga_terendah':
                                            echo 'Harga Terendah';
                                            break;
                                        case 'rating_tertinggi':
                                            echo 'Rating Tertinggi';
                                            break;
                                        default:
                                            echo 'Paling Populer';
                                    }
                                @endphp
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'populer'])) }}">Paling
                                        Populer</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'harga_tertinggi'])) }}">Harga
                                        Tertinggi</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'harga_terendah'])) }}">Harga
                                        Terendah</a></li>
                                <li><a class="dropdown-item"
                                        href="{{ route('destination.browse', array_merge(request()->all(), ['sort' => 'rating_tertinggi'])) }}">Rating
                                        Tertinggi
                                    </a></li>
                            </ul>
                        </div>

                        <div class="gallery-container gallery-fancybox masonry-gallery row">
                            @foreach ($destinations as $destination)
                                <div class="col-xl-4 col-lg-4 col-md-6 col-12 custom-grid" data-wow-duration="2000ms">
                                    <div class="featured-card">
                                        <div class="image">
                                            <div class="img lazy-bg"
                                                data-bg="{{ $destination->gallery[0]->image_url ?? asset('assets/images/default.png') }}">
                                            </div>
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
                                                        <span>{{ number_format($destination->reviews->count() ?? '0', 0, ',', '.') }}</span>
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
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $destinations->links() }}
                </div>
            </div>
        </div>
    </section>
@endsection
