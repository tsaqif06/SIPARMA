@extends('admin.layout.layout')

@php
    $title = 'Data Saldo Biaya Admin';
    $subTitle = 'Saldo Biaya Admin';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>Saldo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($balances as $data)
                            <tr>
                                <td>Rp {{ number_format($data->balance, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
