@extends('admin.layout.layout')

@php
    $title = 'Edit Item Bundle';
    $subTitle = 'Item Bundle - Edit';
    $destinationId = auth()->user()->adminDestinations[0]->destination_id ?? null;
    $destinationName = auth()->user()->adminDestinations[0]->destination->name ?? null;

    $currentItemId = $item->item_id;

    $ridesUrl = route('admin.bundle.items.getrides', ['bundle' => $bundle->id]);

    $script = <<<JS
        <script>
            $(document).ready(function() {
                function populateItemId() {
                    let itemType = $("#item_type").val();
                    let itemSelect = $("#item_id");

                    itemSelect.html("<option>Pilih Item</option>");

                    if (itemType === "destination") {
                        itemSelect.append(
                            `<option value="$destinationId">$destinationName</option>`
                        );
                        itemSelect.val("$destinationId");
                    } else if (itemType === "ride") {
                        $.ajax({
                            url: "$ridesUrl",
                            type: "GET",
                            dataType: "json",
                            success: function(data) {
                                $.each(data, function(index, item) {
                                    let selected = (item.id == $currentItemId) ? "selected" : "";
                                    itemSelect.append(
                                        `<option value="\${item.id}" \${selected}>\${item.name}</option>`
                                    );
                                });

                                itemSelect.val("$currentItemId");
                            },
                            error: function(xhr, status, error) {
                                console.error("Error fetching items:", error);
                            }
                        });
                    }
                }

                populateItemId();

                $("#item_type").change(function() {
                    populateItemId();
                });
            });
        </script>
    JS;

@endphp

@section('content')
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <form action="{{ route('admin.bundle.items.update', ['item' => $item->id, 'bundle' => $bundle->id]) }}"
                        method="POST">
                        @csrf
                        @method('PUT') <!-- Menambahkan method PUT untuk update data -->
                        <div class="row">
                            <input type="hidden" name="bundle_id" value="{{ $bundle->id }}">
                            <!-- Pilih Tipe Item -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="item_type" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Pilih Tipe Item<span class="text-danger-600">*</span>
                                    </label>
                                    <select class="form-control radius-8 @error('item_type') is-invalid @enderror"
                                        id="item_type" name="item_type" required>
                                        <option value="">Pilih Tipe</option>
                                        <option value="destination"
                                            {{ old('item_type', $item->item_type) == 'destination' ? 'selected' : '' }}>
                                            Wisata</option>
                                        <option value="ride"
                                            {{ old('item_type', $item->item_type) == 'ride' ? 'selected' : '' }}>Wahana
                                        </option>
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
                                        @if ($item->item_type == 'destination')
                                            <option value="{{ $destinationId }}" selected>{{ $destinationName }}</option>
                                        @elseif($item->item_type == 'ride')
                                            <!-- List rides bisa ditarik dari controller atau lewat ajax -->
                                        @endif
                                    </select>
                                    @error('item_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            @php
                                $quantityData = json_decode($item->quantity, true);
                            @endphp

                            <!-- Jumlah Dewasa -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="adults" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jumlah Dewasa<span class="text-danger-600">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error('adults') is-invalid @enderror" id="adults"
                                        name="adults" placeholder="Masukkan jumlah dewasa"
                                        value="{{ old('adults', $quantityData['adults'] ?? 0) }}" required>
                                    @error('adults')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Jumlah Anak-anak -->
                            <div class="col-sm-6">
                                <div class="mb-20">
                                    <label for="children" class="form-label fw-semibold text-primary-light text-sm mb-8">
                                        Jumlah Anak-anak<span class="text-danger-600">*</span>
                                    </label>
                                    <input type="number"
                                        class="form-control radius-8 @error('children') is-invalid @enderror" id="children"
                                        name="children" placeholder="Masukkan jumlah anak-anak"
                                        value="{{ old('children', $quantityData['children'] ?? 0) }}" required>
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
