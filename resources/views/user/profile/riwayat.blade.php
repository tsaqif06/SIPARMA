@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-title text-secondary">Menu</div>
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i> Profil</a>
                    <a href="{{ route('transactions.history') }}" style="color: black;"><i class="fas fa-list"></i> Riwayat
                        Transaksi</a>
                    <a href="{{ route('admin.verification') }}"><i class="fas fa-check-circle"></i> Verifikasi Admin</a>
                </div>
            </div>
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card-custom">
                    <h4 class="mb-4">Riwayat Transaksi</h4>
                    <!-- Transaction Table -->
                    <div class="table-responsive">
                        <table class="table" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Gambar</th>
                                    <th>Nama Tiket</th>
                                    <th>ID Transaksi</th>
                                    <th>Tanggal Transaksi</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td><img src="{{ asset(
                                            $transaction->tickets[0]->item_type === 'bundle'
                                                ? $transaction->tickets[0]->item->items[0]->item->gallery[0]->image_url
                                                : $transaction->tickets[0]->item->gallery[0]->image_url,
                                        ) }}"
                                                class="rounded" alt="Gambar Tiket" style="width: 60px;"></td>
                                        <td>Tiket {{ $transaction->tickets[0]->item->name }}</td>
                                        <td>{{ $transaction->transaction_code }}</td>
                                        <td>{{ $transaction->created_at->format('d M Y') }}</td>
                                        <td>
                                            @php
                                                $statusBadge = [
                                                    'pending' => [
                                                        'text' => 'Menunggu Pembayaran',
                                                        'class' => 'warning',
                                                    ],
                                                    'paid' => ['text' => 'Berhasil', 'class' => 'success'],
                                                    'failed' => ['text' => 'Gagal', 'class' => 'danger'],
                                                ];
                                            @endphp

                                            <span class="badge bg-{{ $statusBadge[$transaction->status]['class'] }}">
                                                {{ $statusBadge[$transaction->status]['text'] }}
                                            </span>
                                        </td>

                                        <td class="d-flex justify-content-center">
                                            @if ($transaction->status == 'pending')
                                                <a href="{{ route('payment.show', $transaction->transaction_code) }}"
                                                    target="_blank" class="btn-transparent">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            @elseif ($transaction->status == 'paid')
                                                <a href="{{ route('payment.invoice', $transaction->transaction_code) }}"
                                                    target="_blank" class="btn-transparent">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a class="btn-transparent"
                                                    href="{{ route('payment.invoice.download', $transaction->transaction_code) }}"
                                                    class="btn-transparent">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
