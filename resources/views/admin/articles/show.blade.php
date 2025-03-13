@extends('admin.layout.layout')

@php
    $title = 'Detail Artikel';
    $subTitle = 'Detail Artikel';

    $script = '<script>
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
    <div class="row gy-4">
        <div class="col-lg-12">
            <div class="card h-100">
                <div class="card-body p-24">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-show-article" role="tabpanel"
                            aria-labelledby="pills-show-article-tab" tabindex="0">
                            <div class="row">
                                <!-- Judul Artikel -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Judul Artikel</label>
                                <input type="text" class="form-control" value="{{ $article->title }}" readonly>
                                </div>

                                <!-- Penulis Artikel -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Penulis Artikel</label>
                                    <input type="text" class="form-control" value="{{ $article->user->name }}" readonly>
                                </div>

                                <!-- Kategori -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-semibold">Kategori</label>
                                    <input type="text" class="form-control" value="{{ $article->category->name }}"
                                        readonly>
                                </div>

                                @php
                                    $statusLabels = [
                                        'published' => 'Dipublikasikan',
                                        'draft' => 'Draf',
                                        'blocked' => 'Diblokir',
                                    ];
                                @endphp


                                <!-- Status -->
                                <div class="col-sm-6 mb-3">
                                    <label class="form-label fw-semibold">Status</label>
                                    <input type="text" class="form-control"
                                        value="{{ $statusLabels[$article->status] ?? ucfirst($article->status) }}" readonly>
                                </div>

                                <!-- Thumbnail -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Thumbnail</label>
                                    <div class="mt-2">
                                        <img src="{{ asset(str_replace('public/', '', $article->thumbnail)) }}"
                                            alt="Thumbnail" class="img-fluid" style="max-width: 200px;">
                                    </div>
                                </div>

                                <!-- Konten Artikel -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Isi Artikel</label>
                                    <div class="border p-3 rounded bg-light" style="min-height: 150px;">
                                        {!! $article->content !!}
                                    </div>
                                </div>

                                <!-- Tags -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Tags</label>
                                    <div>
                                        @foreach ($article->tags as $tag)
                                            <span
                                                class="bg-primary-light text-primary-600 px-24 py-4 rounded-pill fw-medium text-sm my-3 mx-2">
                                                {{ $tag->tag_name }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>

                                <!-- Statistik Artikel -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Statistik</label>
                                    <div class="border p-3 rounded bg-light">
                                        <p><strong>Likes:</strong> {{ $article->likes->count() }}</p>
                                        <p><strong>Views:</strong> {{ $article->views->count() }}</p>
                                        <p><strong>Komentar:</strong>
                                            {{ $article->comments->count() + $article->comments->sum(fn($comment) => $comment->replies->count()) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Komentar -->
                                <div class="col-sm-12 mb-3">
                                    <label class="form-label fw-semibold">Komentar</label>
                                    <div class="border p-3 rounded bg-light">
                                        @if ($article->comments->isEmpty())
                                            <p class="text-muted">Belum ada komentar.</p>
                                        @else
                                            @foreach ($article->comments as $comment)
                                                <div class="mb-3 p-2 border-bottom">
                                                    <strong>{{ $comment->user->name }}</strong>
                                                    <span class="text-muted text-sm"> •
                                                        {{ $comment->created_at->diffForHumans() }}</span>
                                                    <p class="mt-2">{{ $comment->comment }}</p>

                                                    <!-- Jika ada reply, tampilkan -->
                                                    @if ($comment->replies->isNotEmpty())
                                                        <div class="ms-4 border-start ps-3">
                                                            @foreach ($comment->replies as $reply)
                                                                <div class="mb-3 p-2 border-bottom">
                                                                    <strong>{{ $reply->user->name }}</strong>
                                                                    <span class="text-muted text-sm"> •
                                                                        {{ $reply->created_at->diffForHumans() }}</span>
                                                                    <p class="mt-2">{{ $reply->comment }}</p>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                </div>

                                <!-- Tombol Kembali & Edit -->
                                <div class="d-flex align-items-center justify-content-center gap-3">
                                    <a href="{{ url()->previous() }}"
                                        class="border border-danger-600 bg-hover-danger-200 text-danger-600 text-md px-56 py-11 radius-8">
                                        Kembali
                                     </a>
                                    @if ($article->status !== 'blocked')
                                        @if (auth()->user()->id === $article->user_id)
                                            <a href="{{ route('admin.articles.edit', $article->id) }}"
                                                class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8">Edit
                                                Artikel</a>
                                        @endif
                                    @else
                                        <form id="deleteForm{{ $article->id }}"
                                            action="{{ route('admin.articles.destroy', $article->id) }}" method="POST"
                                            style="display: inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                class="btn btn-primary border border-primary-600 text-md px-56 py-12 radius-8"
                                                onclick="confirmDelete({{ $article->id }})">Hapus Artikel
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
