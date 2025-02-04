@extends('admin.layout.layout')

@php
    $title = 'Tambah Item Bundle';
    $subTitle = 'Item Bundle - Tambah';
    $destinationId = auth()->user()->adminDestinations[0]->destination_id ?? null;
    $destinationName = auth()->user()->adminDestinations[0]->destination->name ?? null;
    $script = '<script>
        $(document).ready(function() {
            $("#item_type").change(function() {
                let itemType = $(this).val();
                let itemSelect = $("#item_id");

                itemSelect.html("<option>Pilih Item</option>");

                if (itemType === "destination") {
                    itemSelect.append(
                        "<option value=\"' . $destinationId . '\">' . $destinationName . '</option>"
                    );
                    itemSelect.val("' . $destinationId . '");
                    itemSelect.prop("readonly", true);
                } else if (itemType === "ride") {
                    $.ajax({
                        url: `getrides`,
                        type: "GET",
                        dataType: "json",
                        success: function(data) {
                            $.each(data, function(index, item) {
                                itemSelect.append(
                                    `<option value="${item.id}">${item.name}</option>`
                                );
                            });
                        },
                        error: function(xhr, status, error) {
                            console.error("Error fetching items:", error);
                        }
                    });
                    itemSelect.prop("readonly", false);
                } else {
                    itemSelect.prop("readonly", true);
                }
            });
        });
    </script>';
@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <form action="{{ route('admin.bundle.items.store', $bundle->id) }}" method="POST">
                        @csrf
                        <div class="row">
                            <input type="hidden" name="bundle_id">
                            <!-- Pilih Tipe Item -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="item_type" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Pilih Tipe Item<span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-control radius-8 @error('item_type') is-invalid @enderror"
                                        id="item_type" name="item_type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="destination">Wisata</option>
                                        <option value="ride">Wahana</option>
                                    </select>
                                    @error('item_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Pilih Item (Dynamis berdasarkan item_type) -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="item_id" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Pilih Item<span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-control radius-8 @error('item_id') is-invalid @enderror"
                                        id="item_id" name="item_id" required>
                                        <option value="">Pilih Item</option>
                                        <!-- Data akan diisi dengan JavaScript berdasarkan pilihan item_type -->
                                    </select>
                                    @error('item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jumlah Item -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="adults" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jumlah Dewasa<span class="text-danger-600">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error('adults') is-invalid @enderror" id="adults"
                                        name="adults" placeholder="Masukkan jumlah dewasa" value="{{ old('adults', 0) }}"
                                        required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="children" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jumlah Anak-anak<span class="text-danger-600">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error('children') is-invalid @enderror" id="children"
                                        name="children" placeholder="Masukkan jumlah anak-anak"
                                        value="{{ old('children', 0) }}" required>
                                    @error('children')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Tombol Submit -->
                            <div class="d-flex align-items-center justify-content-center gap-3">
                                <button type="submit"
                                    class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
