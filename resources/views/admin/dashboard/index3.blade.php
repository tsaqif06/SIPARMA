@extends('admin.layout.layout')
@php
    $title = 'Selamat Datang, ' . auth()->user()->name . '!';
    $subTitle = 'Dashboard';
    $script = '<script src="' . asset('assets/js/homeThreeChart.js') . '"></script> ';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-xxl-12">
            <div class="card radius-8 border-0">
                <div class="row">
                    <div class="col-xxl-6 pe-xxl-0">
                        <div class="card-body p-24">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                <h6 class="mb-2 fw-bold text-lg">Laporan Pendapatan</h6>
                            </div>
                            <ul class="d-flex flex-wrap align-items-center mt-3 gap-3">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Profit:
                                        <span class="text-primary-light fw-bold">Rp
                                            {{ number_format($total_profit, 0, ',', '.') }}</span>
                                    </span>
                                </li>
                            </ul>
                            <div class="mt-40">
                                <div id="paymentStatusChart" class="margin-16-minus"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xxl-6">
                        <div class="row h-100 g-0">
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            @if (auth()->user()->role == 'superadmin')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-primary-600 bg-primary-light border border-primary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="solar:map-bold" class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                    Wisata</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">{{ $total_destinations }}
                                                </h6>
                                            @elseif (auth()->user()->role == 'admin_wisata')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-primary-600 bg-primary-light border border-primary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="material-symbols:pool-rounded"
                                                        class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                    Wahana</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">{{ $total_rides }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                    {{--  <p class="text-sm mb-0">Increase by <span
                                            class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+200</span>
                                        this week</p>  --}}
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-start-0 border-end-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            @if (auth()->user()->role == 'superadmin')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-secondary-600 bg-secondary-light border border-secondary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="material-symbols:file-map-rounded"
                                                        class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                    Tempat</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">{{ $total_places }}</h6>
                                            @elseif (auth()->user()->role == 'admin_wisata')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-secondary-600 bg-secondary-light border border-secondary-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="uil:transaction" class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Total
                                                    Transaksi</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">{{ $total_transactions }}
                                                </h6>
                                            @endif

                                        </div>
                                    </div>
                                    {{--  <p class="text-sm mb-0">Increase by <span
                                            class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+200</span>
                                        this week</p>  --}}
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-bottom-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            @if (auth()->user()->role == 'superadmin')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-yellow bg-yellow-light border border-yellow-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="flowbite:users-group-solid"
                                                        class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Total User</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">{{ $total_users }}</h6>
                                            @elseif (auth()->user()->role == 'admin_wisata')
                                                <span
                                                    class="mb-12 w-44-px h-44-px text-yellow bg-yellow-light border border-yellow-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                    <iconify-icon icon="fluent:star-12-filled"
                                                        class="icon"></iconify-icon>
                                                </span>
                                                <span class="mb-1 fw-medium text-secondary-light text-md">Rata - Rata
                                                    Rating</span>
                                                <h6 class="fw-semibold text-primary-light mb-1">
                                                    {{ number_format($average_rating, 1) }}</h6>
                                            @endif
                                        </div>
                                    </div>
                                    {{--  <p class="text-sm mb-0">Increase by <span
                                                class="bg-danger-focus px-1 rounded-2 fw-medium text-danger-main text-sm">-5k</span>
                                            this week</p>  --}}
                                </div>
                            </div>
                            <div class="col-6 p-0 m-0">
                                <div
                                    class="card-body p-24 h-100 d-flex flex-column justify-content-center border border-top-0 border-start-0 border-end-0 border-bottom-0">
                                    <div class="d-flex flex-wrap align-items-center justify-content-between gap-1 mb-8">
                                        <div>
                                            <span
                                                class="mb-12 w-44-px h-44-px text-pink bg-pink-light border border-pink-light-white flex-shrink-0 d-flex justify-content-center align-items-center radius-8 h6 mb-12">
                                                <iconify-icon icon="ri:discount-percent-fill" class="icon"></iconify-icon>
                                            </span>
                                            <span class="mb-1 fw-medium text-secondary-light text-md">Total Saldo</span>
                                            <h6 class="fw-semibold text-primary-light mb-1">
                                                Rp {{ number_format($total_balance, 0, ',', '.') }}
                                            </h6>
                                        </div>
                                    </div>
                                    {{--  <p class="text-sm mb-0">Increase by <span
                                            class="bg-success-focus px-1 rounded-2 fw-medium text-success-main text-sm">+$10k</span>
                                        this week</p>  --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xxl-12 col-lg-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Transaksi Terbaru</h6>
                        <a href="{{ route('admin.transactions.index') }}"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            View All
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                    <div class="table-responsive scroll-sm">
                        <table class="table bordered-table mb-0">
                            <thead>
                                <tr>
                                    <th scope="col">Users</th>
                                    <th scope="col">Invoice</th>
                                    <th scope="col">Amount</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col">Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentTransactions as $transaction)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <img src="{{ asset('assets/images/users/default.png') }}" alt=""
                                                    class="flex-shrink-0 me-12 radius-8">
                                                <span class="text-lg text-secondary-light fw-semibold flex-grow-1">
                                                    {{ $transaction->user_name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td>#{{ $transaction->transaction_code }}</td>
                                        <td>Rp {{ number_format($transaction->amount, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <span
                                                class="px-24 py-4 rounded-pill fw-medium text-sm
                                                @if ($transaction->status == 'paid') bg-success-focus text-success-main
                                                @elseif($transaction->status == 'pending') bg-warning-focus text-warning-main
                                                @elseif($transaction->status == 'failed') bg-danger-focus text-danger-main
                                                @else bg-secondary-focus text-secondary-main @endif">
                                                {{ ucfirst($transaction->status) }}
                                            </span>
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d M Y') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada transaksi terbaru.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if (!empty($revenueSeries))
        <script>
            var revenueLabels = @json($revenueLabels);
            var revenueSeries = [{
                name: "Net Profit",
                data: @json($revenueSeries)
            }];
        </script>
    @else
        <script>
            var revenueLabels = [];
            var revenueSeries = [{
                name: "Net Profit",
                data: []
            }];
        </script>
    @endif
@endsection
