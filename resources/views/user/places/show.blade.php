@extends('user.layouts.app')

@section('content')
    <div class="container">
        <h1>{{ $restaurant->name }}</h1>
        <p>{{ $restaurant->description }}</p>
        <p>Location: {{ $restaurant->location }}</p>
    </div>
@endsection
