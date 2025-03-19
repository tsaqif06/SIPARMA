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

    function formatLikes($num)
    {
        if ($num >= 1000000) {
            return number_format($num / 1000000, 1, ',', '.') . 'jt';
        } elseif ($num >= 1000) {
            return number_format($num / 1000, 1, ',', '.') . 'k';
        }
        return $num;
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
                            <h2 style="border-bottom: 0">{{ $article->title }}</h2>
                            <div class="d-flex justify-content-between align-items-center flex-wrap mb-5"
                                style="margin-top: -20px">
                                <!-- Info Penulis -->
                                <div class="d-flex align-items-center gap-2 text-muted">
                                    <iconify-icon icon="solar:user-linear"
                                        style="color: black; font-size: 25px;"></iconify-icon>
                                    <span style="color: black; font-size: 16px;">Diunggah oleh
                                        {{ $article->user->name }}</span>
                                    <span class="ms-4">{{ $article->created_at->format('d M Y') }}</span>
                                </div>

                                <!-- Tombol Like & View -->
                                <div class="d-flex align-items-center gap-3">
                                    <!-- View -->
                                    <div class="d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:eye-linear"
                                            style="color: black; font-size: 25px"></iconify-icon>
                                        <span class="text-muted" style="font-size: 14px;">
                                            {{ formatLikes($article->views->count()) }}
                                        </span>
                                    </div>
                                    <!-- Like -->
                                    <div class="d-flex align-items-center gap-1">
                                        <iconify-icon icon="solar:heart-outline"
                                            style="color: black; font-size: 25px"></iconify-icon>
                                        <span class="text-muted" style="font-size: 14px;">
                                            {{ formatLikes($article->likes->count()) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            @if (!empty($article->thumbnail))
                                <div class="article-thumbnail">
                                    <a
                                        href="{{ asset(
                                            file_exists(public_path($article->thumbnail)) && $article->thumbnail
                                                ? $article->thumbnail
                                                : 'assets/images/default-avatar.jpg',
                                        ) }}">
                                        <div class="img lazy-bg"
                                            data-bg="{{ asset(
                                                file_exists(public_path($article->thumbnail)) && $article->thumbnail
                                                    ? $article->thumbnail
                                                    : 'assets/images/default-avatar.jpg',
                                            ) }}">
                                        </div>
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="article-content mt-5">
                            {!! $article->content !!}
                        </div>

                    </div>

                    <div class="room-review">
                        <div class="room-title">
                            <h2>Ulasan</h2>
                        </div>

                        @if ($reviews->isEmpty())
                            <p>Belum ada ulasan.</p>
                        @else
                            @foreach ($reviews as $review)
                                <div class="review-item">
                                    <div class="review-img">
                                        <div class="img lazy-bg"
                                            data-bg="{{ asset(
                                                file_exists(public_path($review->user->profile_picture)) && $review->user->profile_picture
                                                    ? $review->user->profile_picture
                                                    : 'assets/images/default-avatar.jpg',
                                            ) }}">
                                        </div>
                                    </div>
                                    <div class="review-text">
                                        <div class="r-title">
                                            <h2>{{ $review->user->name }}</h2>
                                            <span class="ms-2">{{ $review->created_at->format('d M Y') }}</span>
                                            <ul style="display: flex;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li>
                                                        <iconify-icon
                                                            icon="{{ $i <= $review->rating ? 'material-symbols:star' : 'material-symbols:star-outline' }}"
                                                            class="menu-icon" style="font-size: 24px; color: gold;">
                                                        </iconify-icon>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                        <p>{{ $review->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                            <div class="pagination mt-4">
                                {{ $reviews->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        @endif
                    </div>

                    @php
                        $userReview = \App\Models\ArticleComment::where('user_id', auth()->id())
                            ->where('article_id', $article->id)
                            ->first();
                    @endphp

                    <div class="add-review">
                        @if ($userReview)
                            <div class="room-title">
                                <h2>Ulasan Anda</h2>
                            </div>
                            <div class="review-item">
                                <div class="review-img">
                                    <div class="img lazy-bg"
                                        data-bg="{{ asset(
                                            file_exists(public_path($review->user->profile_picture)) && $review->user->profile_picture
                                                ? $review->user->profile_picture
                                                : 'assets/images/default-avatar.jpg',
                                        ) }}">
                                    </div>
                                </div>
                                <div class="review-text">
                                    <div class="r-title">
                                        <h2>{{ $userReview->user->name }}</h2>
                                        <span class="ms-2">{{ $userReview->created_at->format('d M Y') }}</span>
                                        <ul style="display: flex;">
                                            @for ($i = 1; $i <= 5; $i++)
                                                <li>
                                                    <iconify-icon
                                                        icon="{{ $i <= $review->rating ? 'material-symbols:star' : 'material-symbols:star-outline' }}"
                                                        class="menu-icon" style="font-size: 24px; color: gold;">
                                                    </iconify-icon>
                                                </li>
                                            @endfor
                                        </ul>
                                    </div>
                                    <p>{{ $userReview->comment }}</p>
                                </div>

                                <div class="review-actions">
                                    <form id="deleteForm{{ $userReview->id }}"
                                        action="{{ route('reviews.destroy', $userReview->id) }}" method="POST"
                                        style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="btn btn-danger"
                                            onclick="confirmDelete({{ $userReview->id }})"><iconify-icon icon="mdi:trash"
                                                width="24" height="24"></iconify-icon></button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <div class="room-title">
                                <h2>Berikan Ulasan</h2>
                            </div>

                            <form action="{{ route('reviews.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="article_id" value="{{ $article->id }}">
                                <div class="wpo-blog-single-section review-form">
                                    <div class="give-rat-sec">
                                        <div class="give-rating">
                                            <label>
                                                <input type="radio" name="rating" value="1"
                                                    {{ old('rating') == 1 ? 'checked' : '' }} required />
                                                <span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="2"
                                                    {{ old('rating') == 2 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="3"
                                                    {{ old('rating') == 3 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="4"
                                                    {{ old('rating') == 4 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span><span class="icon">★</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="rating" value="5"
                                                    {{ old('rating') == 5 ? 'checked' : '' }} />
                                                <span class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span><span class="icon">★</span><span
                                                    class="icon">★</span>
                                            </label>
                                            @error('rating')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="review-add">
                                        <div class="comment-respond">
                                            <div id="commentform" class="comment-form">
                                                <div class="form">
                                                    <textarea id="comment" name="comment" placeholder="Ketik Ulasan" required>{{ old('comment') }}</textarea>
                                                    @error('comment')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <!-- Submit Button -->
                                                <div class="form-submit">
                                                    <button type="submit" class="btn btn-primary"
                                                        style="font-size: 16px;">Kirim
                                                        Ulasan</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @endif
                    </div>
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
                                @if ($article->tags->isEmpty())
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
                                                                : 'assets/images/default.jpg',
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
                                                    <p class="content-clamp">{!! Str::limit(strip_tags($related->content), 80) !!}</p>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        </div>
                        <div class="side-category">
                            <div class="room-title">
                                <h2>Katgeori</h2>
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

    <div class="modal fade" id="laporModal" tabindex="-1" aria-labelledby="laporModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="laporModalLabel">Laporkan Masalah</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('complaints.store') }}" method="POST">
                    @csrf
                    {{--  <input type="hidden" name="place_id" value="{{ $place->id }}">  --}}
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="complaint_text" class="form-label">Isi Laporan</label>
                            <textarea class="form-control" name="complaint_text" rows="4" required placeholder="Tuliskan laporan Anda..."></textarea>
                            @error('complaint_text')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Kirim Laporan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!--End Room-details area-->
@endsection
