@extends('admin.layout.layout')

@php
    $title = 'Data Request Pencairan Saldo';
    $subTitle = 'Request Pencairan Saldo';

    $script = '<script>
        $(".decline-btn").click(function() {
            let id = $(this).data("id");
            let status = $(this).data("status");
            let actionText = status === "completed" ? "menyetujui" : "menolak";
            let confirmButton = status === "completed" ? "Ya, Setujui!" : "Ya, Tolak!";
            let iconType = status === "completed" ? "success" : "error";

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
                            <th>Nama Wisata</th>
                            <th>Jumlah</th>
                            <th>Tanggal Pengajuan</th>
                            <th>Status</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($withdrawals as $data)
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->balance->destination->name }}</td>
                                <td>Rp {{ number_format($withdrawal->amount, 0, ',', '.') }}
                                <td>{{ $withdrawal->created_at->format('d-m-Y') }}</td>
                                <td><span
                                        class="bg-{{ $data->status == 'completed' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-focus text-{{ $data->status == 'completed' ? 'success' : ($data->status == 'pending' ? 'warning' : 'danger') }}-main px-24 py-4 rounded-pill fw-medium text-sm">{{ ucfirst($data->status) }}</span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.places.show', $data->place->id) }}" target="_blank"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                </td>



                                <td>
                                    <!-- Tombol Approve -->
                                    <a href="{{ route('withdrawals.approveForm', $data->id) }}"
                                        class="approve-btn w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:check-circle" class="text-success" width="24"
                                            height="24"></iconify-icon>
                                    </a>

                                    <!-- Tombol Decline -->
                                    <button type="button"
                                        class="decline-btn w-32-px h-32-px bg-danger-focus text-danger-main rounded-circle d-inline-flex align-items-center justify-content-center"
                                        data-id="{{ $data->id }}" data-status="rejected">
                                        <iconify-icon icon="lucide:x-circle" class="text-danger" width="24"
                                            height="24"></iconify-icon>
                                    </button>

                                    <!-- Form untuk submit status -->
                                    <form id="statusForm{{ $data->id }}"
                                        action="{{ route('admin.withdrawal.updateStatus', $data->id) }}" method="POST"
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
@endsection
