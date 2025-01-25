@extends('user.layouts.app')

@section('title', 'Complain')

@section('content')
    <h1>Complain Form</h1>
    <form action="/complain" method="POST">
        @csrf
        <div class="mb-3">
            <label for="complainText" class="form-label">Complain:</label>
            <textarea id="complainText" name="complain_text" class="form-control" rows="4"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
@endsection
