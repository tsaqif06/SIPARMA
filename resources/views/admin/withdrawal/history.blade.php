@extends('admin.layout.layout')

@php
    $title = 'Data Riwayat Pencairan Saldo';
    $subTitle = 'Riwayat Pencairan Saldo';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        #
                                    </label>
                                </div>
                            </th>
                            <th>Nama Wisata</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Tanggal Dicairkan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($withdrawals as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->balance->destination->name }}</td>
                                <td>Rp {{ number_format($data->amount, 0, ',', '.') }}
                                <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                <td>{{ $data->updated_at->format('d-m-Y') }}</td>
                                <td><span
                                        class="bg-{{ $data->status == 'completed' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-focus text-{{ $data->status == 'completed' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-main px-24 py-4 rounded-pill fw-medium text-sm">{{ ucfirst($data->status) }}</span>
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
