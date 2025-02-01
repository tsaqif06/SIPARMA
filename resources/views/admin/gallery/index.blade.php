@extends('admin.layout.layout')
@php
    $title = 'Galeri';
    $subTitle = 'Galeri';
@endphp

@section('content')
    <a href="{{ route('admin.gallery.create', $type) }}">
        <button type="button"
            class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 my-3 d-flex align-items-center gap-2">
            <iconify-icon icon="mingcute:plus-fill" class="text-xl"></iconify-icon> Tambah Data
        </button>
    </a>
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header border-bottom-0 pb-0 pt-0 px-0">
            <ul class="nav border-gradient-tab nav-pills mb-0 border-top-0" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all"
                        type="button" role="tab" aria-controls="pills-all" aria-selected="true">
                        Semua
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-place-tab" data-bs-toggle="pill" data-bs-target="#pills-place"
                        type="button" role="tab" aria-controls="pills-place" aria-selected="false" tabindex="-1">
                        Tempat
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pills-promo-tab" data-bs-toggle="pill" data-bs-target="#pills-promo"
                        type="button" role="tab" aria-controls="pills-promo" aria-selected="false" tabindex="-1">
                        Promo
                    </button>
                </li>
                @if ($menuImages)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="pills-menu-tab" data-bs-toggle="pill" data-bs-target="#pills-menu"
                            type="button" role="tab" aria-controls="pills-menu" aria-selected="false" tabindex="-1">
                            Menu
                        </button>
                    </li>
                @endif
            </ul>
        </div>

        <div class="card-body p-24">
            <div class="tab-content" id="pills-tabContent">
                <!-- All Images Tab -->
                <div class="tab-pane fade show active" id="pills-all" role="tabpanel" aria-labelledby="pills-all-tab"
                    tabindex="0">
                    <div class="row gy-4">
                        @if ($allImages->count() > 0)
                            @foreach ($allImages as $image)
                                <div class="col-xxl-3 col-md-4 col-sm-6">
                                    <a href="{{ asset($image->image_url) }}" target="_blank">
                                        <div class="hover-scale-img border radius-16 overflow-hidden">
                                            <div class="max-h-266-px overflow-hidden">
                                                <img src="{{ asset($image->image_url) }}" alt="{{ $image->image_type }}"
                                                    class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="py-16 px-24">
                                                <form
                                                    action="{{ route('admin.gallery.destroy', ['type' => $type, 'gallery' => $image->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 d-flex align-items-center">
                                                        <iconify-icon icon="mingcute:delete-fill"
                                                            class="text-xl"></iconify-icon> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Belum ada gambar yang diupload.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Place Images Tab -->
                <div class="tab-pane fade" id="pills-place" role="tabpanel" aria-labelledby="pills-place-tab"
                    tabindex="0">
                    <div class="row gy-4">
                        @if ($placeImages->count() > 0)
                            @foreach ($placeImages as $image)
                                <div class="col-xxl-3 col-md-4 col-sm-6">
                                    <a href="{{ asset($image->image_url) }}" target="_blank">
                                        <div class="hover-scale-img border radius-16 overflow-hidden">
                                            <div class="max-h-266-px overflow-hidden">
                                                <img src="{{ asset($image->image_url) }}" alt="Place Image"
                                                    class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="py-16 px-24">
                                                <form
                                                    action="{{ route('admin.gallery.destroy', ['type' => $type, 'gallery' => $image->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 d-flex align-items-center">
                                                        <iconify-icon icon="mingcute:delete-fill"
                                                            class="text-xl"></iconify-icon> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Belum ada gambar tempat yang diupload.</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Promo Images Tab -->
                <div class="tab-pane fade" id="pills-promo" role="tabpanel" aria-labelledby="pills-promo-tab"
                    tabindex="0">
                    <div class="row gy-4">
                        @if ($promoImages->count() > 0)
                            @foreach ($promoImages as $image)
                                <div class="col-xxl-3 col-md-4 col-sm-6">
                                    <a href="{{ asset($image->image_url) }}" target="_blank">
                                        <div class="hover-scale-img border radius-16 overflow-hidden">
                                            <div class="max-h-266-px overflow-hidden">
                                                <img src="{{ asset($image->image_url) }}" alt="Promo Image"
                                                    class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                            </div>
                                            <div class="py-16 px-24">
                                                <form
                                                    action="{{ route('admin.gallery.destroy', ['type' => $type, 'gallery' => $image->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 d-flex align-items-center">
                                                        <iconify-icon icon="mingcute:delete-fill"
                                                            class="text-xl"></iconify-icon> Hapus
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Belum ada gambar promo yang diupload.</p>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($menuImages)
                    <!-- Menu Images Tab -->
                    <div class="tab-pane fade" id="pills-menu" role="tabpanel" aria-labelledby="pills-menu-tab"
                        tabindex="0">
                        <div class="row gy-4">
                            @if ($menuImages->count() > 0)
                                @foreach ($menuImages as $image)
                                    <div class="col-xxl-3 col-md-4 col-sm-6">
                                        <a href="{{ asset($image->image_url) }}" target="_blank">
                                            <div class="hover-scale-img border radius-16 overflow-hidden">
                                                <div class="max-h-266-px overflow-hidden">
                                                    <img src="{{ asset($image->image_url) }}" alt="Menu Image"
                                                        class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                                </div>
                                                <div class="py-16 px-24">
                                                    <form
                                                        action="{{ route('admin.gallery.destroy', ['type' => $type, 'gallery' => $image->id]) }}"
                                                        method="POST">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                            class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 d-flex align-items-center">
                                                            <iconify-icon icon="mingcute:delete-fill"
                                                                class="text-xl"></iconify-icon> Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    <p class="text-center">Belum ada gambar menu yang diupload.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
