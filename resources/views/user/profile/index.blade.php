@extends('user.layouts.app')

@section('content')
    <div class="container">
        <h2>Profil Pengguna</h2>
        <p><strong>Nama:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
    </div>
@endsection
