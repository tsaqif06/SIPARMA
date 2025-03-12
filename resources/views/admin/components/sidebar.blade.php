@php
    $user = auth()->user();

    if ($user === 'admin_wisata') {
        $user->load('adminDestinations');
    } elseif ($user === 'admin_tempat') {
        $user->load('adminPlaces');
    }
@endphp

<aside class="sidebar">
    <button type="button" class="sidebar-close-btn">
        <iconify-icon icon="radix-icons:cross-2"></iconify-icon>
    </button>
    <div>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">

            <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="light-logo">
            <img src="{{ asset('assets/images/logo-light.png') }}" alt="site logo" class="dark-logo">
            {{--  <img src="{{ asset('assets/images/logo.png') }}" alt="site logo" class="logo-icon">  --}}
            {{--  <img src="{{ asset('assets/images/logo-icon.png') }}" alt="site logo" class="logo-icon">  --}}
        </a>
    </div>

    <div class="sidebar-menu-area">
        <ul class="sidebar-menu" id="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}">
                    <iconify-icon icon="solar:home-smile-angle-outline" class="menu-icon"></iconify-icon>
                    <span>Dasboard</span>
                </a>
            </li>
            <li class="sidebar-menu-group-title">Data</li>
            @if ($user && $user->role === 'superadmin')
                <li>
                    <a href="{{ route('admin.users.index') }}">
                        <iconify-icon icon="flowbite:users-group-outline" class="menu-icon"></iconify-icon>
                        <span>Users</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.destinations.index') }}">
                        <iconify-icon icon="material-symbols:map-outline-rounded" class="menu-icon"></iconify-icon>
                        <span>Wisata</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.places.index') }}">
                        <iconify-icon icon="material-symbols:file-map-outline" class="menu-icon"></iconify-icon>
                        <span>Tempat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.articles.index') }}">
                        <iconify-icon icon="grommet-icons:article" class="menu-icon"></iconify-icon>
                        <span>Artikel</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Keuangan</li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <iconify-icon icon="uil:transaction" class="menu-icon"></iconify-icon>
                        <span>Transaksi</span>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="material-symbols:account-balance-wallet-outline"
                            class="menu-icon"></iconify-icon>
                        <span>Saldo Keuangan</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.balance.index') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Saldo Wisata</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.balance.indexAdmin') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Saldo Biaya
                                Admin</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.balance.recapAdmin') }}"><i
                                    class="ri-circle-fill circle-icon text-success-main w-auto"></i> Rekap Saldo Biaya
                                Admin</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">Persetujuan</li>
                <li>
                    <a href="{{ route('admin.places.approval') }}">
                        <iconify-icon icon="lsicon:marketplace-outline" class="menu-icon"></iconify-icon>
                        <span>Admin Tempat</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.destinations.recommendation') }}">
                        <iconify-icon icon="majesticons:map-simple-destination-line" class="menu-icon"></iconify-icon>
                        <span>Rekomendasi Wisata</span>
                    </a>
                </li>
                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="hugeicons:reverse-withdrawal-01" class="menu-icon"></iconify-icon>
                        <span>Pencairan Saldo</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.withdrawal.approval') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Permintaan
                                Pencairan</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.withdrawal.history') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Riwayat
                                Pencairan</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-menu-group-title">Lainnya</li>
                <li>
                    <a href="{{ route('admin.complaints.index') }}">
                        <iconify-icon icon="hugeicons:complaint" class="menu-icon"></iconify-icon>
                        <span>Keluhan</span>
                    </a>
                </li>
            @elseif ($user && $user->role === 'admin_wisata')
                <li>
                    <a href="{{ route('admin.destinations.show', $user->adminDestinations[0]->destination_id) }}">
                        <iconify-icon icon="material-symbols:map-outline-rounded" class="menu-icon"></iconify-icon>
                        <span>Wisata Anda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.gallery.index', 'destination') }}">
                        <iconify-icon icon="material-symbols:gallery-thumbnail-outline-rounded"
                            class="menu-icon"></iconify-icon>
                        <span>Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.facility.index', 'destination') }}">
                        <iconify-icon icon="material-symbols:museum-outline-rounded" class="menu-icon"></iconify-icon>
                        <span>Fasilitas</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.rides.index') }}">
                        <iconify-icon icon="material-symbols:pool-rounded" class="menu-icon"></iconify-icon>
                        <span>Wahana</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Keuangan</li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <iconify-icon icon="uil:transaction" class="menu-icon"></iconify-icon>
                        <span>Transaksi</span>
                    </a>
                </li>

                <li class="dropdown">
                    <a href="javascript:void(0)">
                        <iconify-icon icon="material-symbols:account-balance-wallet-outline"
                            class="menu-icon"></iconify-icon>
                        <span>Saldo Keuangan</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.balance.index') }}">
                                <i class="ri-circle-fill circle-icon text-primary-600 w-auto"></i> Saldo</a>
                        </li>
                        <li>
                            <a href="{{ route('admin.balance.recap') }}"><i
                                    class="ri-circle-fill circle-icon text-warning-main w-auto"></i> Rekap Saldo
                            </a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="{{ route('admin.withdrawal.index') }}">
                        <iconify-icon icon="hugeicons:reverse-withdrawal-01" class="menu-icon"></iconify-icon>
                        <span>Pencairan Saldo</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Lainnya</li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}">
                        <iconify-icon icon="material-symbols:kid-star-outline" class="menu-icon"></iconify-icon>
                        <span>Review</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.promo.index') }}">
                        <iconify-icon icon="material-symbols:money-off-rounded" class="menu-icon"></iconify-icon>
                        <span>Promo</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.bundle.index') }}">
                        <iconify-icon icon="material-symbols:ad-group-outline-rounded"
                            class="menu-icon"></iconify-icon>
                        <span>Bundle</span>
                    </a>
                </li>
            @elseif ($user && $user->role === 'admin_tempat')
                <li>
                    <a href="{{ route('admin.places.show', $user->adminPlaces[0]->place_id) }}">
                        <iconify-icon icon="material-symbols:file-map-outline" class="menu-icon"></iconify-icon>
                        <span>Tempat Anda</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.gallery.index', 'place') }}">
                        <iconify-icon icon="material-symbols:gallery-thumbnail-outline-rounded"
                            class="menu-icon"></iconify-icon>
                        <span>Galeri</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.facility.index', 'place') }}">
                        <iconify-icon icon="material-symbols:museum-outline-rounded" class="menu-icon"></iconify-icon>
                        <span>Fasilitas</span>
                    </a>
                </li>

                <li class="sidebar-menu-group-title">Lainnya</li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}">
                        <iconify-icon icon="material-symbols:kid-star-outline" class="menu-icon"></iconify-icon>
                        <span>Review</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
