@extends('layouts.app')

@section('title', 'Home - Wisata Wahana')

@section('content')
    <h1 class="text-center mb-5">Rekomendasi Wisata Wahana</h1>
    <div class="row">
        @foreach ($recommendedDestinations as $destination)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $destination->image_url }}" class="card-img-top" alt="{{ $destination->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $destination->name }}</h5>
                        <p class="card-text">{{ $destination->description }}</p>
                        <a href="{{ route('destinations.show', $destination->id) }}" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <h2 class="mt-5">Promo Wisata Wahana</h2>
    <div class="row">
        @foreach ($promoDestinations as $promo)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $promo->image_url }}" class="card-img-top" alt="{{ $promo->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $promo->name }}</h5>
                        <p class="card-text">Discount: {{ $promo->discount }}%</p>
                        <a href="{{ route('destinations.show', $promo->id) }}" class="btn btn-success">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
