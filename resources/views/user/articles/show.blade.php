@extends('user.layouts.app')

@php
    $script = '<script>
        //---------------------------
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

    if (!function_exists('formatLikes')) {
        function formatLikes($num)
        {
            if ($num >= 1000000) {
                return number_format($num / 1000000, 1, ',', '.') . 'jt';
            } elseif ($num >= 1000) {
                return number_format($num / 1000, 1, ',', '.') . 'k';
            }
            return $num;
        }
    }
@endphp

@section('content')
    <!--Start Room-details area-->
    <div class="room-details-area section-padding" style="margin-top: -100px;">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 col-12">
                    <div class="room-description">
                        <div class="room-title">
                            <!-- Judul Artikel -->
                            <h1 class="display-5 fw-bold mb-3 text-dark text-center text-md-start">{{ $article->title }}</h1>

                            <!-- Meta Informasi -->
                            <div
                                class="d-flex flex-column flex-md-row justify-content-between align-items-center border-bottom pb-3 mb-4">
                                <!-- Informasi Penulis -->
                                <div class="d-flex align-items-center gap-3">
                                    <iconify-icon icon="solar:user-linear" class="meta-icon"></iconify-icon>
                                    <div>
                                        <span class="d-block text-dark fw-semibold">Diunggah oleh
                                            {{ $article->user->name }}</span>
                                        <span
                                            class="text-muted small">{{ $article->created_at->format('d M Y, H:i') }}</span>
                                    </div>
                                </div>

                                <!-- Statistik Artikel -->
                                <div
                                    class="d-flex flex-wrap gap-4 justify-content-center justify-content-md-end py-2 stats">
                                    <!-- Views -->
                                    <div class="d-flex align-items-center gap-2 text-muted">
                                        <iconify-icon icon="solar:eye-linear" class="meta-icon"></iconify-icon>
                                        <span class="small">{{ formatLikes($article->views->count()) }}</span>
                                    </div>

                                    <!-- Likes -->
                                    @php
                                        $isLiked = $article->likes->where('user_id', auth()->id())->count() > 0;
                                    @endphp
                                    <button id="like-btn"
                                        class="d-flex align-items-center gap-2 border-0 bg-transparent text-muted p-1"
                                        onclick="toggleLike({{ $article->id }})">
                                        <iconify-icon id="like-icon"
                                            icon="{{ $isLiked ? 'solar:heart-bold' : 'solar:heart-outline' }}"
                                            class="meta-icon {{ $isLiked ? 'text-danger' : '' }}"></iconify-icon>
                                        <span id="like-count"
                                            class="small">{{ formatLikes($article->likes->count()) }}</span>
                                    </button>

                                    <!-- Comments -->
                                    <a href="#comments"
                                        class="d-flex align-items-center gap-2 text-decoration-none text-muted">
                                        <iconify-icon icon="solar:chat-line-linear" class="meta-icon"></iconify-icon>
                                        <span class="small">{{ formatLikes($article->comments->count()) }}</span>
                                    </a>
                                </div>
                            </div>

                            <!-- Thumbnail Artikel -->
                            <div class="mb-3 rounded-3 overflow-hidden shadow-sm">
                                <img src="{{ asset(file_exists(public_path($article->thumbnail)) && $article->thumbnail ? $article->thumbnail : 'assets/images/default.png') }}"
                                    alt="{{ $article->title }}" class="img-fluid w-100 lazy" loading="lazy"
                                    style="object-fit: cover; height: auto;">
                            </div>

                            <!-- Share Button -->
                            <div class="d-flex justify-content-center justify-content-md-end">
                                <div class="dropdown">
                                    <button
                                        class="btn btn-outline-secondary btn-sm dropdown-toggle d-flex align-items-center gap-1"
                                        type="button" id="shareDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        <iconify-icon icon="solar:share-linear" class="fs-6"></iconify-icon>
                                        <span>Bagikan</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="shareDropdown">
                                        <li><a class="dropdown-item d-flex align-items-center gap-2"
                                                href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}"
                                                target="_blank">
                                                <iconify-icon icon="logos:facebook" class="fs-6"></iconify-icon>
                                                Facebook</a></li>
                                        <li><a class="dropdown-item d-flex align-items-center gap-2"
                                                href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($article->title) }}"
                                                target="_blank">
                                                <iconify-icon icon="logos:twitter" class="fs-6"></iconify-icon>
                                                Twitter</a></li>
                                        <li><a class="dropdown-item d-flex align-items-center gap-2"
                                                href="https://wa.me/?text={{ urlencode($article->title . ' ' . url()->current()) }}"
                                                target="_blank">
                                                <iconify-icon icon="logos:whatsapp-icon" class="fs-6"></iconify-icon>
                                                WhatsApp</a></li>
                                        <li>
                                            <hr class="dropdown-divider">
                                        </li>
                                        <li>
                                            <button class="dropdown-item d-flex align-items-center gap-2"
                                                onclick="copyToClipboard('{{ url()->current() }}')">
                                                <iconify-icon icon="solar:link-linear" class="fs-6"></iconify-icon> Copy
                                                Link
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="article-content mt-3">
                            {!! $article->content !!}
                        </div>
                    </div>

                    <div class="room-review" id="comments">
                        <div class="room-title">
                            <h2>Komentar</h2>
                        </div>

                        @if ($reviews->isEmpty())
                            <p>Belum ada komentar.</p>
                        @else
                            @foreach ($reviews as $review)
                                <div class="review-item mb-4 p-3 border rounded shadow-sm">
                                    <div class="d-flex align-items-start">
                                        <!-- Foto Profil -->
                                        <div class="review-img me-3">
                                            <div class="img-comment lazy-bg"
                                                data-bg="{{ asset($review->user->profile_picture ?: 'assets/images/default-avatar.jpg') }}">
                                            </div>
                                        </div>

                                        <!-- Konten Komentar -->
                                        <div class="review-text flex-grow-1">
                                            <div class="r-title d-flex align-items-center">
                                                <h2 class="fs-5 mb-0 fw-bold">{{ $review->user->name }}</h2>
                                                <span
                                                    class="ms-2 text-muted small">{{ $review->created_at->format('d M Y') }}</span>
                                            </div>

                                            <p class="mt-2 mb-2">{{ $review->comment }}</p>

                                            <!-- Tombol Balas -->
                                            <button
                                                class="btn btn-sm btn-outline-primary d-inline-flex align-items-center gap-1"
                                                data-bs-toggle="collapse" data-bs-target="#reply-form-{{ $review->id }}">
                                                <iconify-icon icon="solar:chat-line-linear"
                                                    style="font-size: 18px;"></iconify-icon>
                                                Balas
                                            </button>

                                            <!-- Form Balasan -->
                                            <div id="reply-form-{{ $review->id }}" class="collapse my-2">
                                                <form method="POST"
                                                    action="{{ route('comment.reply.store', $review->id) }}">
                                                    @csrf
                                                    <textarea class="form-control" name="comment" rows="2" placeholder="Tulis balasan..." required></textarea>
                                                    <button type="submit" class="btn btn-sm btn-primary mt-2">
                                                        <iconify-icon icon="solar:send-linear"
                                                            style="font-size: 18px;"></iconify-icon>
                                                        Kirim
                                                    </button>
                                                </form>
                                            </div>

                                            <!-- Tampilkan Balasan -->
                                            @if ($review->replies->isNotEmpty())
                                                <button
                                                    class="btn btn-sm btn-outline-secondary d-inline-flex align-items-center gap-1 mt-2"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#replies-{{ $review->id }}">
                                                    <iconify-icon icon="solar:eye-linear"
                                                        style="font-size: 18px;"></iconify-icon>
                                                    Lihat Balasan ({{ $review->replies->count() }})
                                                </button>

                                                <div id="replies-{{ $review->id }}"
                                                    class="collapse mt-3 ms-4 border-start ps-3">
                                                    @foreach ($review->replies as $reply)
                                                        <div class="review-item reply-item d-flex align-items-start mb-3">
                                                            <!-- Foto Profil Balasan -->
                                                            <div class="review-img reply-img me-2">
                                                                <div class="img-reply lazy-bg"
                                                                    data-bg="{{ asset($reply->user->profile_picture ?: 'assets/images/default-avatar.jpg') }}">
                                                                </div>
                                                            </div>

                                                            <!-- Konten Balasan -->
                                                            <div class="review-text reply-text">
                                                                <div class="r-title reply-title d-flex align-items-center">
                                                                    <h3 class="fs-6 mb-0 fw-bold">{{ $reply->user->name }}
                                                                    </h3>
                                                                    <span
                                                                        class="ms-2 text-muted small">{{ $reply->created_at->format('d M Y') }}</span>
                                                                </div>

                                                                <p class="small mt-2">{{ $reply->comment }}</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif

                        <!-- PAGINATION -->
                        <div class="mt-3">
                            {{ $reviews->links('vendor.pagination.bootstrap-5') }}
                        </div>
                    </div>

                    @php
                        $userReview = \App\Models\ArticleComment::where('user_id', auth()->id())
                            ->where('article_id', $article->id)
                            ->whereNull('parent_id')
                            ->first();
                    @endphp

                    @if (!$userReview)
                        <div class="add-review">
                            <div class="room-title">
                                <h2>Berikan Komentar</h2>
                            </div>

                            <form action="{{ route('comment.store', $article->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                <div class="wpo-blog-single-section review-form">
                                    <div class="review-add">
                                        <div class="comment-respond">
                                            <div id="commentform" class="comment-form">
                                                <div class="form">
                                                    <textarea id="comment" name="comment" placeholder="Ketik Komentar" required>{{ old('comment') }}</textarea>
                                                    @error('comment')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-submit">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="font-size: 16px;">Kirim
                                                        Komentar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>
                <div class="col-lg-4 col-12">
                    <div class="d-none d-md-block mt-5"></div>
                    <div class="blog-sidebar room-sidebar">
                        <form action="{{ route('article.browse') }}" method="GET">
                            <input type="text" name="search" class="search-box" placeholder="Cari Artikel"
                                value="{{ request('search') }}">
                        </form>
                        <div class="side-relevan mt-5">
                            <div class="room-title">
                                <h2>Artikel Relevan</h2>
                            </div>
                            <div class="fasilitas-list">
                                @if ($relatedArticles->isEmpty())
                                    <p>Tidak ada artikel relevan</p>
                                @else
                                    @foreach ($relatedArticles as $related)
                                        <a href="{{ route('article.show', $related->slug) }}" class="related-article">
                                            <div class="related-item">
                                                <!-- Thumbnail -->
                                                <div class="related-img">
                                                    <div class="img lazy-bg"
                                                        data-bg="{{ asset(
                                                            file_exists(public_path($related->thumbnail)) && $related->thumbnail
                                                                ? $related->thumbnail
                                                                : 'assets/images/default.png',
                                                        ) }}">
                                                    </div>
                                                </div>

                                                <!-- Text Container -->
                                                <div class="related-text mt-2">
                                                    <!-- Title -->
                                                    <div class="r-title">
                                                        <h2 class="title-clamp">{{ $related->title }}</h2>
                                                    </div>

                                                    <!-- Content -->
                                                    <p class="content-clamp">{!! Str::limit(
                                                        preg_replace('/<figure[^>]*>.*?<\/figure>/', '', strip_tags(html_entity_decode($related->content))),
                                                        100,
                                                    ) !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="side-category">
                            <div class="room-title">
                                <h2>Kategori</h2>
                            </div>
                            <div class="fasilitas-list">
                                <p>{{ ucfirst($article->category->name) }}</p>
                            </div>
                        </div>
                        <div class="side-tag">
                            <div class="room-title">
                                <h2>Tags</h2>
                            </div>
                            <div class="fasilitas-list">
                                @if ($article->tags->isEmpty())
                                    <p>Tidak ada tag</p>
                                @else
                                    @foreach ($article->tags as $tag)
                                        <span class="fasilitas-item">{{ ucfirst($tag->tag_name) }}</span>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        var isLoggedIn = {{ auth()->check() ? 'true' : 'false' }};

        function copyToClipboard(text) {
            navigator.clipboard.writeText(text).then(function() {
                alert('Link berhasil disalin!');
            }, function(err) {
                console.error('Gagal menyalin: ', err);
            });
        }

        function formatLikes(num) {
            if (num >= 1000000) {
                return (num / 1000000).toFixed(1).replace(".", ",") + "jt";
            } else if (num >= 1000) {
                return (num / 1000).toFixed(1).replace(".", ",") + "k";
            }
            return num;
        }

        function toggleLike(articleId) {
            if (!isLoggedIn) {
                window.location.href = "{{ route('login') }}";
                return;
            }

            $.ajax({
                url: "{{ route('article.like') }}",
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    article_id: articleId
                },
                success: function(response) {
                    if (response.status === 'liked') {
                        $("#like-icon").attr("icon", "solar:heart-bold").css("color", "red");
                    } else {
                        $("#like-icon").attr("icon", "solar:heart-outline").css("color", "#595c5f");
                    }

                    // Gunakan formatLikes sebelum menampilkan
                    $("#like-count").text(formatLikes(response.likes));
                },
                error: function(xhr) {
                    alert("Gagal melakukan aksi.");
                }
            });
        }
    </script>
@endsection
