@extends('admin.layout.layout')
@php
    $title = 'List Review';
    $subTitle = 'List Review';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Nama User</th>
                            <th scope="col" style="width: 130px">Rating</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($reviews as $review)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $review->user->name }}</td>
                                <td>
                                    @for ($j = 1; $j <= 5; $j++)
                                        <iconify-icon icon="bi:star-fill"
                                            class="{{ $j <= $review->rating ? 'text-warning' : 'text-secondary-light' }} d-inline-block"></iconify-icon>
                                    @endfor
                                </td>
                                <td>{{ $review->created_at->format('d M Y, H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.reviews.show', $review->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
