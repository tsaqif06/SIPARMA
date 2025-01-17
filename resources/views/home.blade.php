@extends('layouts.app')

@section('title', 'Home Page')

@push('styles')
    <style>
        .welcome-banner {
            background-color: #f8f9fa;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
    </style>
@endpush

@section('content')
    <div class="welcome-banner">
        <h1>Welcome to App Name</h1>
        <p>This is the home page of your application.</p>
    </div>
@endsection

@push('scripts')
    <script>
        console.log('Home Page Loaded!');
    </script>
@endpush
