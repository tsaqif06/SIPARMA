@extends('admin.layout.layout')

@php
    $title = 'Data Wahana ' . auth()->user()->adminDestinations[0]->destination->name;
    $subTitle = 'Wahana';
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
    <a href="{{ route('admin.rides.create') }}">
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
                            <th scope="col">Harga</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($rides as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->name }}</td>
                                <td>Rp {{ number_format($data->price, 0, ',', '.') }}</td>
                                <td>
                                    @php $bg = $data->operational_status == 'Buka' ? 'success' : 'danger'; @endphp
                                    <span
                                        class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">{{ $data->operational_status }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.rides.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.rides.edit', $data->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ route('admin.rides.destroy', $data->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                            onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </button>
                                    </form>
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
