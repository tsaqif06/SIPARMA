@extends('user.layouts.app')

@section('content')
    <div class="container">
        <!-- Bagian Promo -->
        @foreach ($promos as $type => $promo)
            <h2>Promo {{ $type }}</h2>
            <div class="row">
                @foreach ($promo as $prom)
                    <div class="col-md-3">
                        <div class="card">
                            <img src="{{ $prom->image_url ?? 'https://picsum.photos/200.webp' }}" class="card-img-top"
                                alt="{{ $prom->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $prom->name }}</h5>
                                <!-- Menampilkan rating rata-rata -->
                                <p class="card-text">
                                    Rating: {{ number_format($prom->reviews_avg_rating, 1) }} / 5
                                </p>
                                <a href="{{ route('destination.show', $prom->slug) }}" class="btn btn-primary">View
                                    More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach

        <hr>

        <!-- Bagian Kategori -->
        @foreach ($categories as $type => $destinations)
            <h2>{{ ucfirst($type) }} Destinations</h2>
            <div class="row">
                @foreach ($destinations as $destination)
                    <div class="col-md-3">
                        <div class="card">
                            <img src="{{ $destination->image_url ?? 'https://picsum.photos/200.webp' }}"
                                class="card-img-top" alt="{{ $destination->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $destination->name }}</h5>
                                <!-- Menampilkan rating rata-rata -->
                                <p class="card-text">
                                    Rating: {{ number_format($destination->reviews_avg_rating, 1) }} / 5
                                </p>
                                <a href="{{ route('destination.show', $destination->slug) }}" class="btn btn-primary">View
                                    More</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection
