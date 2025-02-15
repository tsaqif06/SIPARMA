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
            @endif

            @if ($user && $user->role === 'superadmin')
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

                <li class="sidebar-menu-group-title">Keuangan</li>
                <li>
                    <a href="{{ route('admin.transactions.index') }}">
                        <iconify-icon icon="uil:transaction" class="menu-icon"></iconify-icon>
                        <span>Transaksi</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.balance.index') }}">
                        <iconify-icon icon="material-symbols:account-balance-wallet-outline"
                            class="menu-icon"></iconify-icon>
                        <span>Saldo Keuangan</span>
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
                        <iconify-icon icon="material-symbols:ad-group-outline-rounded" class="menu-icon"></iconify-icon>
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

            @if ($user && $user->role === 'superadmin')
                <li class="sidebar-menu-group-title">Persetujuan</li>
                <li>
                    <a href="{{ route('admin.places.approval') }}">
                        <iconify-icon icon="material-symbols:ad-group-outline-rounded" class="menu-icon"></iconify-icon>
                        <span>Admin Tempat</span>
                    </a>
                </li>
            @endif
        </ul>
    </div>
</aside>
