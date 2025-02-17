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
                    <hr>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt"></i>
                            Logout</button>
                    </form>
                </div>
            </div>
            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card-custom">
                    <h4 class="mb-4">Riwayat Transaksi</h4>
                    <!-- Transaction Table -->
                    <div class="container mt-4">
                        <div class="row row-cols-1 g-4">
                            @foreach ($transactions as $transaction)
                                <div class="col">
                                    <div class="card h-100 shadow-sm p-3">
                                        <div class="row g-0">
                                            <div class="col-md-2 d-flex align-items-center">
                                                <img src="{{ asset(
                                                    $transaction->tickets[0]->item_type === 'bundle'
                                                        ? $transaction->tickets[0]->item->items[0]->item->gallery[0]->image_url ?? 'assets/images/default.png'
                                                        : $transaction->tickets[0]->item->gallery[0]->image_url ?? 'assets/images/default.png',
                                                ) }}"
                                                    style="width: 100%; border-radius: 8px" alt="Tiket">
                                            </div>
                                            <div class="col-md-3 d-flex flex-column justify-content-center ms-md-3 mt-sm-2">
                                                <h5 class="mb-2">Tiket {{ $transaction->tickets[0]->item->name }}</h5>
                                                <p class="mb-0">IDR
                                                    {{ number_format($transaction->tickets[0]->item->price, 0, ',', '.') }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                <p class="mb-0">{{ $transaction->created_at->format('d M Y') }}
                                                </p>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center justify-content-center my-sm-2">
                                                @if ($transaction->status == 'pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                @elseif ($transaction->status == 'paid')
                                                    <span class="badge bg-success">Berhasil</span>
                                                @elseif ($transaction->status == 'failed')
                                                    <span class="badge bg-danger">Gagal</span>
                                                @endif
                                            </div>
                                            <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                @if ($transaction->status == 'pending')
                                                    <a href="{{ route('payment.show', $transaction->transaction_code) }}"
                                                        target="_blank" class="btn btn-outline-primary btn-sm me-1"
                                                        style="background-color: #27374D"><i class="fas fa-eye"></i></a>
                                                @elseif ($transaction->status == 'paid')
                                                    <a href="{{ route('payment.invoice', $transaction->transaction_code) }}"
                                                        target="_blank" class="btn btn-success btn-sm me-1"
                                                        style="background-color: #27374D"><i class="fas fa-eye"></i></a>
                                                    <a href="{{ route('payment.invoice.download', $transaction->transaction_code) }}"
                                                        class="btn btn-info btn-sm" style="background-color: #2B80F4"><i
                                                            class="fas fa-download" style="color: white;"></i></a>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
