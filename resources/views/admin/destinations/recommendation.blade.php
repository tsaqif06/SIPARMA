@extends('admin.layout.layout')

@php
    $title = 'Data Rekomendasi Wisata';
    $subTitle = 'Rekomendasi Wisata';
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
                            <th scope="col">Nama Perekomendasi</th>
                            <th scope="col">Nama Tempat Wisata</th>
                            <th scope="col">Lokasi</th>
                            <th scope="col">Deskripsi</th>
                            <th scope="col">Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($recommendations as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->destination_name }}</td>
                                <td>{{ json_decode($data->location)->address }}</td>
                                <td>{{ $data->description }}</td>
                                <td>
                                    @php
                                        // Menentukan status dalam Bahasa Indonesia
                                        $statusIndo = '';
                                        if ($data->status == 'pending') {
                                            $statusIndo = 'Menunggu'; // Pending = Menunggu
                                        } elseif ($data->status == 'approved') {
                                            $statusIndo = 'Disetujui'; // Approved = Disetujui
                                        } elseif ($data->status == 'rejected') {
                                            $statusIndo = 'Ditolak'; // Rejected = Ditolak
                                        }

                                        // Menentukan warna berdasarkan status
                                        $bg = '';
                                        if ($data->status == 'pending') {
                                            $bg = 'warning'; // Kuning
                                        } elseif ($data->status == 'approved') {
                                            $bg = 'success'; // Hijau
                                        } elseif ($data->status == 'rejected') {
                                            $bg = 'danger'; // Merah
                                        }
                                    @endphp

                                    <span
                                        class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">
                                        {{ $statusIndo }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.recommendations.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.recommendations.changeStatus', $data->id) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
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
