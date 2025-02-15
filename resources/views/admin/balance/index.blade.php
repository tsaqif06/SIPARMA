@extends('admin.layout.layout')

@php
    $title = 'Data Transaksi';
    $subTitle = 'Transaksi';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Wisata</th>
                            <th>Saldo</th>
                            <th>Total Profit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($balances as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->destination->name }}</td>
                                <td>Rp {{ number_format($data->balance, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($data->total_profit, 0, ',', '.') }}</td>
                                <td>
                                    <a href="{{ route('admin.balance.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
