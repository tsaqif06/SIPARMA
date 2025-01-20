@extends('layouts.app')

@section('content')
    <div class="container">
        <!-- Bagian Promo -->
        <h2>Promo Destinations</h2>
        <div class="row">
            @foreach ($promoDestinations as $promo)
                <div class="col-md-3">
                    <div class="card">
                        <img src="{{ $promo->image_url ?? 'default.jpg' }}" class="card-img-top" alt="{{ $promo->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $promo->name }}</h5>
                            <!-- Menampilkan rating rata-rata -->
                            <p class="card-text">
                                Rating: {{ number_format($promo->reviews_avg_rating, 1) }} / 5
                            </p>
                            <a href="{{ route('destinations.show', $promo->id) }}" class="btn btn-primary">View More</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Bagian Kategori -->
        @foreach ($categories as $type => $destinations)
            <h2>{{ ucfirst($type) }} Destinations</h2>
            <div class="row">
                @foreach ($destinations as $destination)
                    <div class="col-md-3">
                        <div class="card">
                            <img src="{{ $destination->image_url ?? 'https://picsum.photos/200' }}" class="card-img-top"
                                alt="{{ $destination->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $destination->name }}</h5>
                                <!-- Menampilkan rating rata-rata -->
                                <p class="card-text">
                                    Rating: {{ number_format($destination->reviews_avg_rating, 1) }} / 5
                                </p>
                                <a href="{{ route('destinations.show', $destination->id) }}" class="btn btn-primary">View
                                    More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
