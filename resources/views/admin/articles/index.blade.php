@extends('admin.layout.layout')

@php
    $title = 'Data Artikel';
    $subTitle = 'Artikel';

    $script = '<script>
        $(".remove-item-btn").on("click", function() {
            $(this).closest("tr").addClass("d-none")
        });

        function confirmDelete(userId) {
            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data ini akan diblokir secara permanen!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Blokir",
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
                            <th>#</th>
                            <th>Judul Artikel</th>
                            <th>Penulis Artikel</th>
                            <th>Jumlah Like</th>
                            <th>Jumlah Komentar</th>
                            <th>Jumlah View</th>
                            <th>Waktu Dibuat</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($articles as $data)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $data->title }}</td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->likes->count() }}</td>
                                <td>{{ $data->comments->count() + $data->comments->sum(fn($comment) => $comment->replies->count()) }}
                                </td>
                                <td>{{ $data->views->count() }}</td>
                                <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $bg = $data->status == 'published' ? 'success' : 'warning';
                                    @endphp
                                    <span
                                        class="bg-{{ $bg }}-focus text-{{ $bg }}-main px-24 py-4 rounded-pill fw-medium text-sm">
                                        {{ $data->status == 'published' ? 'Dipublikasikan' : 'Draf' }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.articles.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ route('admin.articles.block', $data->id) }}" method="POST"
                                        style="display: inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="button"
                                            class="w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                            onclick="confirmDelete({{ $data->id }})">
                                            <iconify-icon icon="mdi:block"></iconify-icon>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
