@extends('admin.layout.layout')
@php
    $title = 'Galeri';
    $subTitle = 'Galeri';
@endphp

@section('content')
    <div class="card h-100 p-0 radius-12 overflow-hidden">
        <div class="card-header border-bottom-0 pb-0 pt-0 px-0">
            <ul class="nav border-gradient-tab nav-pills mb-0 border-top-0" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="pills-all-tab" data-bs-toggle="pill" data-bs-target="#pills-all"
                        type="button" role="tab" aria-controls="pills-all" aria-selected="true">
                        All
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
                                    <div class="hover-scale-img border radius-16 overflow-hidden">
                                        <div class="max-h-266-px overflow-hidden">
                                            <img src="{{ asset($image->image_url) }}" alt="{{ $image->image_type }}"
                                                class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
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
                                    <div class="hover-scale-img border radius-16 overflow-hidden">
                                        <div class="max-h-266-px overflow-hidden">
                                            <img src="{{ asset($image->image_url) }}" alt="Place Image"
                                                class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
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
                                    <div class="hover-scale-img border radius-16 overflow-hidden">
                                        <div class="max-h-266-px overflow-hidden">
                                            <img src="{{ asset($image->image_url) }}" alt="Promo Image"
                                                class="hover-scale-img__img w-100 h-100 object-fit-cover">
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="text-center">Belum ada gambar promo yang diupload.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
