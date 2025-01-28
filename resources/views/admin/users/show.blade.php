@extends('admin.layout.layout')
@php
    $title = 'Lihat Profil';
    $subTitle = 'Profil - Lihat';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-4">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <img src="{{ asset('assets/images/user-grid/user-grid-bg1.png') }}" alt=""
                    class="w-100 object-fit-cover" style="height: 150px">
                <div class="pb-24 ms-16 mb-24 me-16  mt--100">
                    <div class="text-center border border-top-0 border-start-0 border-end-0">
                        <img src="{{ $user->profile_picture }}" alt="{{ $user->name }}"
                            class="border br-white border-width-2-px w-200-px h-200-px rounded-circle object-fit-cover">
                        <h6 class="mb-0 mt-16">{{ $user->name }}</h6>
                        <span class="text-secondary-light mb-16">{{ $user->email }}</span>
                    </div>
                    <div class="mt-24">
                        <h6 class="text-xl mb-16">Informasi Pribadi</h6>
                        @php
                            if ($user->role == 'superadmin') {
                                $user->role = 'Super Admin';
                            } elseif ($user->role == 'admin_wisata') {
                                $user->role = 'Admin Wisata';
                            } elseif ($user->role == 'admin_tempat') {
                                $user->role = 'Admin Tempat';
                            }
                        @endphp
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light"> Role</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->role }}</span>
                        </li>
                        <li class="d-flex align-items-center gap-1 mb-12">
                            <span class="w-30 text-md fw-semibold text-primary-light">No. Telepon</span>
                            <span class="w-70 text-secondary-light fw-medium">: {{ $user->phone_number }}</span>
                        </li>
                        @if ($managedItems)
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Nomor Akun</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ $managedItems->destination->account_number ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Nama Akun</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ $managedItems->destination->account_name ?? '-' }}</span>
                            </li>
                            <li class="d-flex align-items-center gap-1 mb-12">
                                <span class="w-30 text-md fw-semibold text-primary-light">Nama Bank</span>
                                <span class="w-70 text-secondary-light fw-medium">:
                                    {{ $managedItems->destination->bank_name ?? '-' }}</span>
                            </li>
                        @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="user-grid-card position-relative border radius-16 overflow-hidden bg-base h-100">
                <div class="pb-24 ms-16 mb-24 me-16 mt-16">
                    <h6 class="text-xl mb-16">Tempat Yang Diurus</h6>
                    <hr class="mb-16">
                    @if ($managedItems)
                        <ul>
                            <li>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        @if ($user->role === 'Admin Wisata' && $managedItems->destination)
                                            <!-- Menampilkan destinasi wisata -->
                                            <h6 class="mb-0 fw-semibold text-primary-light">
                                                {{ $managedItems->destination->name }}</h6>
                                            <small class="text-secondary-light">
                                                Lokasi: {{ $managedItems->destination->location }}
                                            </small>
                                            <p class="text-secondary-light mb-0">Tipe:
                                                {{ ucfirst($managedItems->destination->type) }}</p>
                                            <p class="text-secondary-light">Status:
                                                {{ ucfirst($managedItems->destination->operational_status) }}</p>
                                        @elseif ($user->role === 'Admin Tempat' && $managedItems->place)
                                            <!-- Menampilkan tempat -->
                                            <h6 class="mb-0 fw-semibold text-primary-light">
                                                {{ $managedItems->place->name }}</h6>
                                            <small class="text-secondary-light">
                                                Lokasi: {{ $managedItems->place->location }}
                                            </small>
                                            <p class="text-secondary-light mb-0">Tipe:
                                                {{ ucfirst($managedItems->place->type) }}</p>
                                            <p class="text-secondary-light">Status:
                                                {{ ucfirst($managedItems->place->operational_status) }}</p>
                                        @else
                                            <p>Data tidak tersedia.</p>
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <h6 class="text-lg text-primary">Galeri</h6>
                                    @if (
                                        $user->role === 'Admin Wisata' &&
                                            $managedItems->destination &&
                                            $managedItems->destination->gallery &&
                                            $managedItems->destination->gallery->isNotEmpty())
                                        <div class="row">
                                            @foreach ($managedItems->destination->gallery as $image)
                                                <div class="col-md-4">
                                                    <img src="{{ asset($image->image_url) }}" alt="Gallery Image"
                                                        class="img-fluid rounded mb-3">
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif (
                                        $user->role === 'Admin Tempat' &&
                                            $managedItems->place &&
                                            $managedItems->place->gallery &&
                                            $managedItems->place->gallery->isNotEmpty())
                                        <div class="row">
                                            @foreach ($managedItems->place->gallery as $image)
                                                <div class="col-md-4">
                                                    <img src="{{ asset($image->image_url) }}" alt="Gallery Image"
                                                        class="img-fluid rounded mb-3">
                                                </div>
                                            @endforeach
                                        </div>
                                    @else
                                        <p>Tidak ada gambar yang tersedia.</p>
                                    @endif
                                </div>
                            </li>
                        </ul>
                    @else
                        <p>Tidak ada tempat yang diurus oleh pengguna ini.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
