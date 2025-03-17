@extends('admin.layout.layout')
@php
    $title = 'Dashboard';
    $subTitle = 'eCommerce';
@endphp

@section('content')
    <div class="row gy-4">
        <!-- Card Statistik -->
        <div class="col-xxl-3 col-lg-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <h6 class="mb-2 fw-bold text-lg">Total Pengguna</h6>
                    <h2 class="fw-semibold text-primary-light mb-1">{{ $total_users }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <h6 class="mb-2 fw-bold text-lg">Total Destinasi</h6>
                    <h2 class="fw-semibold text-primary-light mb-1">{{ $total_destinations }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <h6 class="mb-2 fw-bold text-lg">Total Tempat</h6>
                    <h2 class="fw-semibold text-primary-light mb-1">{{ $total_places }}</h2>
                </div>
            </div>
        </div>
        <div class="col-xxl-3 col-lg-6">
            <div class="card radius-8 border-0">
                <div class="card-body p-24">
                    <h6 class="mb-2 fw-bold text-lg">Total Profit</h6>
                    <h2 class="fw-semibold text-primary-light mb-1">${{ number_format($total_profit, 2) }}</h2>
                </div>
            </div>
        </div>

        <!-- Revenue Report Chart -->
        <div class="col-xxl-6">
            <div class="card radius-8 border-0">
                <div class="row">
                    <div class="col-xxl-6 pe-xxl-0">
                        <div class="card-body p-24">
                            <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between">
                                <h6 class="mb-2 fw-bold text-lg">Revenue Report</h6>
                                <div class="">
                                    <select class="form-select form-select-sm w-auto bg-base border text-secondary-light">
                                        <option>Yearly</option>
                                        <option>Monthly</option>
                                        <option>Weekly</option>
                                        <option>Today</option>
                                    </select>
                                </div>
                            </div>
                            <ul class="d-flex flex-wrap align-items-center mt-3 gap-3">
                                <li class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px radius-2 bg-primary-600"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Earning:
                                        <span
                                            class="text-primary-light fw-bold">${{ number_format($total_profit, 2) }}</span>
                                    </span>
                                </li>
                                <li class="d-flex align-items-center gap-2">
                                    <span class="w-12-px h-12-px radius-2 bg-yellow"></span>
                                    <span class="text-secondary-light text-sm fw-semibold">Expense:
                                        <span class="text-primary-light fw-bold">$20,000.00</span>
                                    </span>
                                </li>
                            </ul>
                            <div class="mt-40">
                                <div id="paymentStatusChart" class="margin-16-minus"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders Chart -->
        <div class="col-xxl-6">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="d-flex align-items-center flex-wrap gap-2 justify-content-between mb-20">
                        <h6 class="mb-2 fw-bold text-lg mb-0">Recent Orders</h6>
                        <a href="javascript:void(0)"
                            class="text-primary-600 hover-text-primary d-flex align-items-center gap-1">
                            View All
                            <iconify-icon icon="solar:alt-arrow-right-linear" class="icon"></iconify-icon>
                        </a>
                    </div>
                    <div id="recent-orders"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Chart -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Data dari Controller
        const revenueLabels = @json($revenueLabels);
        const revenueSeries = @json($revenueSeries);
        const recentOrdersLabels = @json($recentOrdersLabels);
        const recentOrdersSeries = @json($recentOrdersSeries);

        // Revenue Report Chart
        var revenueOptions = {
            series: [{
                name: 'Revenue',
                data: revenueSeries
            }],
            colors: ['#487FFF'],
            chart: {
                type: 'bar',
                height: 250,
                toolbar: {
                    show: false
                },
            },
            xaxis: {
                categories: revenueLabels,
            },
        };
        var revenueChart = new ApexCharts(document.querySelector("#paymentStatusChart"), revenueOptions);
        revenueChart.render();

        // Recent Orders Chart
        var recentOrdersOptions = {
            series: [{
                name: 'Orders',
                data: recentOrdersSeries
            }],
            chart: {
                type: 'area',
                height: 360,
                toolbar: {
                    show: false
                },
            },
            xaxis: {
                categories: recentOrdersLabels,
            },
        };
        var recentOrdersChart = new ApexCharts(document.querySelector('#recent-orders'), recentOrdersOptions);
        recentOrdersChart.render();
    </script>
@endsection
