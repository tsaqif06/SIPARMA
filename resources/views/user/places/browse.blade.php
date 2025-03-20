@extends('user.layouts.app')

@section('content')
    <section class="verifikasi-section section-padding" style="margin-top: -150px">
        <div class="container mt-5 d-flex">
            <!-- Sidebar Filter -->
            <div class="row">
                <div class="sidebar-filter col-md-3 col-12" style="height: 330px">
                    <form action="{{ route('place.browse') }}" method="GET">
                        <input type="text" name="search" class="search-box" placeholder="Cari tempat"
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
                                    <div class="featured-card d-flex flex-column h-100" style="min-height: 500px;">
                                        <div class="image">
                                            <div class="img lazy-bg"
                                                data-bg="{{ $place->gallery[0]->image_url ?? 'assets/images/default.png' }}">
                                            </div>
                                        </div>
                                        <div class="content flex-grow-1 d-flex flex-column">
                                            <h2>
                                                <a href="{{ route('place.show', $place->slug) }}">{{ $place->name }}</a>
                                            </h2>
                                            <div class="description flex-grow-1 d-flex align-items-start"
                                                style="min-height: 50px;">
                                                <span class="overflow-hidden"
                                                    style="
                                                    display: -webkit-box;
                                                    -webkit-line-clamp: 3;
                                                    -webkit-box-orient: vertical;
                                                ">
                                                    {{ $place->description ?? 'Deskripsi tidak tersedia.' }}
                                                </span>
                                            </div>
                                            <div class="top-content mt-auto">
                                                <ul>
                                                    <li>
                                                        <span>
                                                            {{ number_format($place->facilities->count() ?? '0', 0, ',', '.') }}</span>
                                                        <span class="date">Fasilitas</span>
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
    </section>
@endsection
