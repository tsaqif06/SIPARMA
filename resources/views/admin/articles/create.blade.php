@extends('admin.layout.layout')

@php
    $title = 'Tambah Artikel';
    $subTitle = 'Tambah Artikel';

    $script = '<script>
        function previewThumbnail(event) {
            let preview = document.getElementById("thumbnail-preview");
            let file = event.target.files[0];

            if (file) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            const tagInput = document.getElementById("tag-input");
            const tagContainer = document.getElementById("tag-container");
            const tagsField = document.getElementById("tags");
            let tags = [];

            tagInput.addEventListener("keypress", function(event) {
                if (event.key === "Enter" && tagInput.value.trim() !== "") {
                    event.preventDefault();
                    const tagText = tagInput.value.trim();

                    if (!tags.includes(tagText)) {
                        tags.push(tagText);
                        const tagElement = document.createElement("span");
                        tagElement.classList.add("bg-primary-light", "text-primary-600", "px-24", "py-4",
                            "rounded-pill", "fw-medium", "text-sm", "my-3", "mx-2");
                        tagElement.innerHTML =
                            `${tagText} <button type="button" class="btn-close btn-sm text-white ms-4" aria-label="Close"></button>`;
                        tagContainer.appendChild(tagElement);

                        tagElement.querySelector("button").addEventListener("click", function() {
                            tags = tags.filter(tag => tag !== tagText);
                            tagElement.remove();
                            tagsField.value = tags.join(",");
                        });

                        tagsField.value = tags.join(",");
                    }

                    tagInput.value = "";
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
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-add-article" role="tabpanel"
                            aria-labelledby="pills-add-article-tab" tabindex="0">
                            <form action="{{ route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <!-- Judul Artikel -->
                                    <div class="col-sm-12 mb-3">
                                        <label for="title" class="form-label fw-semibold">Judul Artikel</label>
                                        <input type="text" name="title" id="title"
                                            class="form-control @error('title') is-invalid @enderror"
                                            value="{{ old('title') }}">
                                        @error('title')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Kategori -->
                                    <div class="col-sm-6 mb-3">
                                        <label for="category_id" class="form-label fw-semibold">Kategori</label>
                                        <select name="category_id" id="category_id"
                                            class="form-select @error('category_id') is-invalid @enderror">
                                            <option value="">Pilih Kategori</option>
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}"
                                                    {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('category_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Status -->
                                    <div class="col-sm-6 mb-3">
                                        <label for="status" class="form-label fw-semibold">Status</label>
                                        <select name="status" id="status"
                                            class="form-select @error('status') is-invalid @enderror">
                                            <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft
                                            </option>
                                            <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>
                                                Published</option>
                                        </select>
                                        @error('status')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Thumbnail -->
                                    <div class="col-sm-12 mb-3">
                                        <label for="thumbnail" class="form-label fw-semibold">Thumbnail</label>
                                        <input type="file" name="thumbnail" id="thumbnail"
                                            class="form-control @error('thumbnail') is-invalid @enderror"
                                            onchange="previewThumbnail(event)">
                                        <div class="mt-2">
                                            <img id="thumbnail-preview" src="#" alt="Preview" class="img-fluid"
                                                style="max-width: 200px; display: none;">
                                        </div>
                                        @error('thumbnail')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Konten Artikel -->
                                    <div class="col-sm-12 mb-3">
                                        <label for="content" class="form-label fw-semibold">Isi Artikel</label>
                                        <input id="content" type="hidden" name="content" value="{{ old('content') }}">
                                        <trix-editor input="content"></trix-editor>
                                        @error('content')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <!-- Tags -->
                                    <div class="col-sm-12 mb-3">
                                        <label for="tags" class="form-label fw-semibold">Tags</label>
                                        <input type="text" id="tag-input" class="form-control"
                                            placeholder="Tekan Enter untuk menambah tag">
                                        <div id="tag-container" class="mt-2"></div>
                                        <input type="hidden" name="tags" id="tags">
                                    </div>

                                    <!-- Tombol Submit -->
                                    <div class="d-flex align-items-center justify-content-center gap-3">
                                        <a href="{{ route('admin.articles.my') }}"
                                            class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">Kembali</a>
                                        <button type="submit"
                                            class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Simpan
                                            Artikel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
