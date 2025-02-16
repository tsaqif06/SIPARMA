@extends('admin.layout.layout')

@php
    $title = 'Data Rekap Saldo';
    $subTitle = 'Rekap Saldo';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Bulan</th>
                            <th>Tahun</th>
                            <th>Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($balanceLogs as $log)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ date('F', mktime(0, 0, 0, $log->period_month, 1)) }}</td>
                                <td>{{ $log->period_year }}</td>
                                <td>Rp {{ number_format($log->profit, 2, ',', '.') }}</td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
