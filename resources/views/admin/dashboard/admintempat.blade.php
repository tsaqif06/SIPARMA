@extends('admin.layout.layout')
@php
    $title = 'Selamat Datang, ' . auth()->user()->name . '!';
    $subTitle = 'Dashboard';
    $script = '<script src="' . asset('assets/js/homeThreeChart.js') . '"></script> ';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-xxl-12">
            <div class="card radius-8 border-0">
                <div class="row">
                    <div class="col-xxl-12">
                        <div class="row h-100 g-0">
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span
                                                class="mb-12 w-44-px h-44-px text-primary-600 bg-primary-light border border-primary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                <iconify-icon icon="grommet-icons:article" class="icon"></iconify-icon>
                                            </span>
                                            <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                Artikel</span>
                                            <h6 class="fw-semibold text-primary-light mb-1">{{ $total_article }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-start-0 border-end-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span
                                                class="mb-12 w-44-px h-44-px text-secondary-600 bg-secondary-light border border-secondary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                <iconify-icon icon="material-symbols:gallery-thumbnail-outline-rounded"
                                                    class="icon"></iconify-icon>
                                            </span>
                                            <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                Galeri</span>
                                            <h6 class="fw-semibold text-primary-light mb-1">{{ $total_gallery }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-bottom-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span
                                                class="mb-12 w-44-px h-44-px text-yellow bg-yellow-light border border-yellow-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                <iconify-icon icon="fluent:star-12-filled" class="icon"></iconify-icon>
                                            </span>
                                            <span class="mb-1 fw-medium text-secondary-light text-md">Rata - Rata
                                                Rating Tempat</span>
                                            <h6 class="fw-semibold text-primary-light mb-1">
                                                {{ number_format($average_rating, 1) }}</h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-start-0 border-end-0 border-bottom-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span
                                                class="mb-12 w-44-px h-44-px text-pink bg-pink-light border border-pink-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                <iconify-icon icon="material-symbols:museum-outline-rounded"
                                                    class="icon"></iconify-icon>
                                            </span>
                                            <span class="mb-1 fw-medium text-secondary-light text-md">Total Fasilitas</span>
                                            <h6 class="fw-semibold text-primary-light mb-1">
                                                {{ $total_facility }}
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
