@extends('admin.layout.layout')
@php
    $title = 'Data User';
    $subTitle = 'Users';
    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });

        function confirmDelete(userId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini akan dihapus secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#deleteForm" + userId).submit();
                }
            });
        }
    </script>';
@endphp

@section('content')
    <a href="{{ route('admin.users.create') }}">
        <button type="button"
            class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 my-3 d-flex align-items-center gap-2">
            <iconify-icon icon="mingcute:plus-fill" class="text-xl"></iconify-icon> Tambah Data
        </button>
    </a>
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th scope="col">
                                <div class="form-check style-check d-flex align-items-center">
                                    <label class="form-check-label">
                                        #
                                    </label>
                                </div>
                            </th>
                            <th scope="col">Nama</th>
                            <th scope="col">Email</th>
                            <th scope="col">No. Telp</th>
                            <th scope="col">Role</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($users as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ asset($data->profile_picture ?? 'assets/images/default-avatar.jpg') }}"
                                            alt="{{ $data->name }}" class="flex-shrink-0 me-12 radius-8" width="50">
                                        {{--  <img src="{{ asset(`storage/profilepicture/{$data->profile_picture}`) }}"
                                            alt="" class="flex-shrink-0 me-12 radius-8">  --}}
                                        <h6 class="text-md mb-0 fw-medium flex-grow-1">{{ $data->name }}</h6>
                                    </div>
                                </td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->phone_number }}</td>
                                @php
                                    if ($data->role === 'superadmin') {
                                        $data->role = 'Super Admin';
                                        $color = 'bg-warning-focus text-warning-main';
                                    } elseif ($data->role === 'admin_wisata') {
                                        $data->role = 'Admin Wisata';
                                        $color = 'bg-success-focus text-success-main';
                                    } elseif ($data->role === 'admin_tempat') {
                                        $data->role = 'Admin Tempat';
                                        $color = 'bg-danger-focus text-danger-main';
                                    } else {
                                        $data->role = 'User';
                                        $color = 'bg-primary-light text-primary-600';
                                    }
                                @endphp
                                <td>
                                    <span
                                        class="{{ $color }} px-24 py-4 rounded-pill fw-medium text-sm">{{ $data->role }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.users.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.users.edit', $data->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    @if ($data->role !== 'Super Admin')
                                        <form id="deleteForm{{ $data->id }}"
                                            action="{{ route('admin.users.destroy', $data->id) }}" method="POST"
                                            style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                                onclick="confirmDelete({{ $data->id }})">
                                                <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
