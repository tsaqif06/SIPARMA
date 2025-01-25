@extends('user.layouts.app')

@section('title', 'Home - Restoran')

@section('content')
    <h1 class="text-center mb-5">Restoran Terbaik</h1>
    <div class="row">
        @foreach ($topRatedRestaurants as $restaurant)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $restaurant->image_url }}" class="card-img-top" alt="{{ $restaurant->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $restaurant->name }}</h5>
                        <p class="card-text">Rating: {{ $restaurant->rating }}/5</p>
                        <a href="{{ route('place.show', $restaurant->id) }}" class="btn btn-primary">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <h2 class="mt-5">Promo Restoran</h2>
    <div class="row">
        @foreach ($promoRestaurants as $promo)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <img src="{{ $promo->image_url }}" class="card-img-top" alt="{{ $promo->name }}">
                    <div class="card-body">
                        <h5 class="card-title">{{ $promo->name }}</h5>
                        <p class="card-text">Discount: {{ $promo->discount }}%</p>
                        <a href="{{ route('place.show', $promo->id) }}" class="btn btn-success">Details</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
