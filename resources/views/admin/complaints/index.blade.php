@extends('admin.layout.layout')

@php
    $title = 'Data Laporan Keluhan';
    $subTitle = 'Laporan Keluhan Pengguna';
@endphp

@section('content')
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Pelapor</th>
                            <th>Wisata</th>
                            <th>Tempat</th>
                            <th>Isi Keluhan</th>
                            <th>Waktu Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($complaints as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->destination->name ?? '-' }}</td>
                                <td>{{ $data->place->name ?? '-' }}</td>
                                <td>{{ $data->complaint_text }}</td>
                                <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $bg =
                                            $data->status == 'new'
                                                ? 'warning'
                                                : ($data->status == 'resolved'
                                                    ? 'success'
                                                    : 'danger');
                                    @endphp
                                    <span
                                        class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">
                                        {{ $data->status == 'new' ? 'Baru' : ($data->status == 'resolved' ? 'Selesai' : 'Ditutup') }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.complaints.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.complaints.edit', $data->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
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
