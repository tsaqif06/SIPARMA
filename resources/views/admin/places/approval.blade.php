@extends('admin.layout.layout')

@php
    $title = 'Data Request Admin Tempat';
    $subTitle = 'Request Admin Tempat';
    $script = '<script>
        $(".approve-btn, .decline-btn").click(function() {
            let id = $(this).data("id");
            let status = $(this).data("status");
            let actionText = status === "approved" ? "menyetujui" : "menolak";
            let confirmButton = status === "approved" ? "Ya, Setujui!" : "Ya, Tolak!";
            let iconType = status === "approved" ? "success" : "error";

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: `Anda akan ${actionText} tempat ini.`,
                icon: iconType,
                showCancelButton: true,
                confirmButtonText: confirmButton,
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    $("#statusInput" + id).val(status);
                    $("#statusForm" + id).submit();
                }
            });
        });

        //$(".view-pdf").click(function() {
        //    let pdfUrl = $(this).data("pdf");
        //    $("#pdfViewer").attr("src", "https://docs.google.com/gview?embedded=true&url=" + encodeURIComponent(
        //        pdfUrl));
        //    $("#pdfModal").modal("show");
        //});
    </script>';
@endphp

@section('content')
    <a href="{{ route('admin.places.create') }}">
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
                            <th scope="col">Nama User</th>
                            <th scope="col">Nama Tempat</th>
                            <th scope="col">Detail Tempat</th>
                            <th scope="col">Tanda Kepemilikan</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($adminplaces as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->user->name }}</td>
                                <td>{{ $data->place->name }}</td>
                                <td>
                                    <a href="{{ route('admin.places.show', $data->place->id) }}" target="_blank"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                </td>

                                <td>
                                    @if ($data->ownership_docs)
                                        {{--  <button class="btn btn-info btn-sm view-pdf"
                                            data-pdf="{{ asset($data->ownership_docs) }}">
                                            Pratinjau PDF
                                        </button>  --}}
                                        <a href="{{ asset($data->ownership_docs) }}">
                                            <button class="btn btn-info btn-sm view-pdf">
                                                Pratinjau PDF
                                            </button>
                                        </a>
                                    @else
                                        <span>Tidak ada dokumen</span>
                                    @endif
                                </td>

                                <td>
                                    <!-- Tombol Approve -->
                                    <button type="button"
                                        class="approve-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                        data-id="{{ $data->id }}" data-status="approved">
                                        <iconify-icon icon="lucide:check-circle" class="text-success" width="24"
                                            height="24"></iconify-icon>
                                    </button>

                                    <!-- Tombol Decline -->
                                    <button type="button"
                                        class="decline-btn w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                        data-id="{{ $data->id }}" data-status="rejected">
                                        <iconify-icon icon="lucide:x-circle" class="text-danger" width="24"
                                            height="24"></iconify-icon>
                                    </button>

                                    <!-- Form untuk submit status -->
                                    <form id="statusForm{{ $data->id }}"
                                        action="{{ route('admin.places.updateStatus', $data->id) }}" method="POST"
                                        style="display: none;">
                                        @csrf
                                        <input type="hidden" name="status" id="statusInput{{ $data->id }}">
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

    {{--  <div id="pdfModal" class="modal fade" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pratinjau Dokumen</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <iframe id="pdfViewer" src="" width="100%" height="500px"></iframe>
                </div>
            </div>
        </div>
    </div>  --}}
@endsection
