@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $destination->name }}</h1>
        <p>{{ $destination->description }}</p>
        <p>Location: {{ $destination->location }}</p>
        <p>Price: Rp {{ number_format($destination->price, 0, ',', '.') }}</p>
        <p>Weekday Price: Rp {{ number_format($destination->weekday_price, 0, ',', '.') }}</p>
        <p>Weekend Price: Rp {{ number_format($destination->weekend_price, 0, ',', '.') }}</p>
    </div>
@endsection
