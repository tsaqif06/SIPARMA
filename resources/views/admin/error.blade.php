@extends('admin.layout.layout')

@php
    $title = $status_code;
    $subTitle = $status_code;
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body py-80 px-32 text-center">
            {{--  <img src="{{ asset('assets/images/error-img.png') }}" alt="" class="mb-24">  --}}
            <h6 class="mb-16">{{ $status_code }} - {{ $error }}</h6>
            <p class="text-secondary-light">{{ $message }}</p>
            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary-600 radius-8 px-20 py-11">Kembali ke Dashboard</a>
        </div>
    </div>
@endsection
