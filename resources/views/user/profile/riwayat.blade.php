@extends('user.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <!-- Sidebar -->
            <div class="col-md-3">
                <div class="sidebar">
                    <div class="sidebar-title text-secondary">{{ __('main.menu') }}</div>
                    <a href="{{ route('profile') }}"><i class="fas fa-user"></i>
                        {{ __('main.profil') }}</a>
                    <a href="{{ route('transactions.history') }}" style="color: black;"><i class="fas fa-list"></i>
                        {{ __('main.riwayat_transaksi') }}</a>
                    <a href="{{ route('admin.verification') }}"><i class="fas fa-check-circle"></i>
                        {{ __('main.verifikasi_admin') }}</a>
                    <hr>
                    <form action="{{ route('logout') }}" method="POST" class="mt-2">
                        @csrf
                        <button type="submit" class="btn btn-danger w-100"><i class="fas fa-sign-out-alt"></i>
                            {{ __('main.logout') }}</button>
                    </form>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="card-custom">
                    <h4 class="mb-4">{{ __('main.riwayat_transaksi') }}</h4>
                    <!-- Transaction Table -->
                    <div class="container mt-4">
                        @if ($transactions->isEmpty())
                            <div class="text-center py-5">
                                <div class="mb-3">
                                    <i class="fas fa-calendar-times fa-4x text-muted"></i>
                                </div>
                                <h5 class="text-muted">{{ __('main.belum_ada_transaksi') }}</h5>
                                <p class="text-muted">{{ __('main.silahkan_lakukan_transaksi') }}</p>
                            </div>
                        @else
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
                                                        style="width: 100%; border-radius: 8px"
                                                        alt="{{ __('main.tiket') }}">
                                                </div>
                                                <div
                                                    class="col-md-3 d-flex flex-column justify-content-center ms-md-3 mt-sm-2">
                                                    <h5 class="mb-2">{{ __('main.tiket') }}
                                                        {{ $transaction->tickets[0]->item->getTranslatedName() }}</h5>
                                                    <p class="mb-0">IDR
                                                        {{ number_format($transaction->amount + config('app.admin_fee'), 0, ',', '.') }}
                                                    </p>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-center justify-content-center">
                                                    <p class="mb-0">{{ $transaction->created_at->format('d M Y') }}
                                                    </p>
                                                </div>
                                                <div
                                                    class="col-md-2 d-flex align-items-center justify-content-center my-sm-2">
                                                    @if ($transaction->status == 'pending')
                                                        <span class="badge bg-warning">{{ __('main.menunggu') }}</span>
                                                    @elseif ($transaction->status == 'paid')
                                                        <span class="badge bg-success">{{ __('main.berhasil') }}</span>
                                                    @elseif ($transaction->status == 'failed')
                                                        <span class="badge bg-danger">{{ __('main.gagal') }}</span>
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
                                {{ $transactions->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
