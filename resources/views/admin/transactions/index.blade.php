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
                            <th>Kode Transaksi</th>
                            <th>Nama User</th>
                            <th>Nama Wisata</th>
                            <th>Nama Tiket</th>
                            <th>Jumlah Tiket</th>
                            <th>Jumlah Pembayaran</th>
                            <th>Tanggal Transaksi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($transactions as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ ucfirst($data->transaction_code) }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->destination->name }}</td>
                                <td>
                                    @foreach ($data->tickets as $ticket)
                                        TIket {{ $ticket->item->name }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($data->tickets as $ticket)
                                        {{ $ticket->item_type == 'destination' ? 'Wisata' : 'Wahana' }}:
                                        {{ $ticket->adult_count }} Dewasa,
                                        {{ $ticket->children_count }} Anak<br>
                                    @endforeach
                                </td>
                                <td>Rp {{ number_format($data->amount, 0, ',', '.') }}</td>
                                <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                <td><span
                                        class="bg-{{ $data->status == 'paid' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-focus text-{{ $data->status == 'paid' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-main px-24 py-4 rounded-pill fw-medium text-sm">{{ ucfirst($data->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.transactions.show', $data->transaction_code) }}"
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
