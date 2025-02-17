@extends('admin.layout.layout')

@php
    $title = 'Data Rekap Saldo Biaya Admin';
    $subTitle = 'Rekap Saldo Biaya Admin';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th class="text-start">Bulan</th>
                            <th class="text-start">Tahun</th>
                            <th class="text-start">Profit</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($balanceLogs as $log)
                            <tr>
                                <td class="text-start">{{ date('F', mktime(0, 0, 0, $log->period_month, 1)) }}</td>
                                <td class="text-start">{{ $log->period_year }}</td>
                                <td class="text-start">Rp {{ number_format($log->profit, 2, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
