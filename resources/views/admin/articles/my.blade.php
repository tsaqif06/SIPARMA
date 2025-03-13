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
    <a href="{{ route('admin.articles.create') }}">
        <button type="button"
            class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 my-3 d-flex align-items-center gap-2">
            <iconify-icon icon="mingcute:plus-fill" class="text-xl"></iconify-icon> Tambah Artikel
        </button>
    </a>
    <div class="card basic-data-table">
        <div class="card-body">
            <div class="table-responsive scroll-sm">
                <table class="table bordered-table sm-table mb-0" id="dataTable" data-page-length='10'>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Judul Artikel</th>
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
                                <td>{{ $data->likes->count() }}</td>
                                <td>{{ $data->comments->count() + $data->comments->sum(fn($comment) => $comment->replies->count()) }}
                                </td>
                                <td>{{ $data->views->count() }}</td>
                                <td>{{ $data->created_at->format('d-m-Y') }}</td>
                                <td>
                                    @php
                                        $statusLabels = [
                                            'published' => 'Dipublikasikan',
                                            'draft' => 'Draf',
                                            'blocked' => 'Diblokir',
                                        ];
                                    @endphp

                                    <span
                                        class="bg-{{ $data->status == 'published' ? 'success' : ($data->status == 'draft' ? 'warning' : 'danger') }}-focus 
                                            text-{{ $data->status == 'published' ? 'success' : ($data->status == 'draft' ? 'warning' : 'danger') }}-main 
                                            px-24 py-4 rounded-pill fw-medium text-sm">
                                        {{ $statusLabels[$data->status] ?? ucfirst($data->status) }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.articles.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    @if ($data->status !== 'blocked')
                                        <a href="{{ route('admin.articles.edit', $data->id) }}"
                                            class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                    @endif
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ route('admin.articles.destroy', $data->id) }}" method="POST"
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
                            <?php $i++; ?>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
