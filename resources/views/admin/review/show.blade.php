@extends('admin.layout.layout')

@php
    $title = 'Lihat Review';
    $subTitle = 'Review - Lihat';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="row">
                        <!-- Nama Reviewer -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="name" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Nama Reviewer
                                </label>
                                <input type="text" class="form-control radius-8" id="name" name="name"
                                    value="{{ $review->user->name }}" readonly>
                            </div>
                        </div>

                        <!-- Email Reviewer -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="email" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Email Reviewer
                                </label>
                                <input type="text" class="form-control radius-8" id="email" name="email"
                                    value="{{ $review->user->name }}" readonly>
                            </div>
                        </div>

                        <!-- Rating -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="rating" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Rating
                                </label>
                                <br>
                                @for ($j = 1; $j <= 5; $j++)
                                    <iconify-icon icon="bi:star-fill"
                                        class="{{ $j <= $review->rating ? 'text-warning' : 'text-secondary-light' }} d-inline-block"></iconify-icon>
                                @endfor
                            </div>
                        </div>

                        <!-- Dibuat pada -->
                        <div class="col-sm-6">
                            <div class="mb-20">
                                <label for="cretaed_at" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Dibuat Pada
                                </label>
                                <input type="text" class="form-control radius-8" id="cretaed_at" name="cretaed_at"
                                    value="{{ $review->created_at }}" readonly>
                            </div>
                        </div>

                        <!-- Komentar -->
                        <div class="col-sm-12">
                            <div class="mb-20">
                                <label for="comment" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                    Komentar
                                </label>
                                <textarea class="form-control radius-8" id="comment" name="comment" rows="4" readonly>{{ $review->comment }}</textarea>
                            </div>
                        </div>

                        <!-- Tombol Submit -->
                        <div class="d-flex align-items-center justify-content-center gap-3">
                            <a href="{{ route('admin.reviews.index') }}"
                                class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
