@extends('admin.layout.layout')

@php
    $discountedPrice =
        $bundle->discount > 0
            ? $bundle->total_price - $bundle->total_price * ($bundle->discount / 100)
            : $bundle->total_price;

    $title = 'Data Item Bundle ' . $bundle->name . ' - ';
    if ($bundle->discount > 0) {
        $title .=
            '<del class="text-secondary" style="text-decoration: line-through;">Rp. ' .
            number_format($bundle->total_price, 0, ',', '.') .
            '</del> ';
        $title .= '<span>Rp. ' . number_format($discountedPrice, 0, ',', '.') . '</span>';
    } else {
        $title .= 'Rp. ' . number_format($bundle->total_price, 0, ',', '.');
    }

    $subTitle = 'Item Bundle';
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
    <a href="{{ route('admin.bundle.items.create', $bundle->id) }}">
        <button type="button"
            class="btn rounded-pill btn-primary-600 radius-8 px-20 py-11 my-3 d-flex align-items-center gap-2">
            <iconify-icon icon="mingcute:plus-fill" class="text-xl"></iconify-icon> Tambah Item
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
                            <th scope="col">Nama Tiket</th>
                            <th scope="col">Tipe Item</th>
                            <th scope="col">Kuantitas</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach ($items as $data)
                            @php
                                $quantity = json_decode($data->quantity, true);
                                $adults = $quantity['adults'] ?? 0;
                                $children = $quantity['children'] ?? 0;
                            @endphp
                            <tr>
                                <td>
                                    <div class="form-check style-check d-flex align-items-center">
                                        <label class="form-check-label">
                                            {{ $i }}
                                        </label>
                                    </div>
                                </td>
                                <td>{{ $data->item->name }}</td>
                                <td>{{ $data->item_type == 'destination' ? 'Destinasi' : 'Wahana' }}</td>
                                <td>
                                    Dewasa: {{ $adults }}, Anak: {{ $children }}
                                </td>
                                <td>
                                    <a href="{{ route('admin.rides.show', $data->id) }}"
                                        class="w-32-px h-32-px bg-primary-light text-primary-600 rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="iconamoon:eye-light"></iconify-icon>
                                    </a>
                                    <a href="{{ route('admin.bundle.items.edit', ['item' => $data->id, 'bundle' => $bundle->id]) }}"
                                        class="w-32-px h-32-px bg-success-focus text-success-main rounded-circle d-inline-flex align-items-center justify-content-center">
                                        <iconify-icon icon="lucide:edit"></iconify-icon>
                                    </a>
                                    <form id="deleteForm{{ $data->id }}"
                                        action="{{ route('admin.bundle.items.destroy', ['item' => $data->id, 'bundle' => $bundle->id]) }}"
                                        method="POST" style="display: inline">
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
