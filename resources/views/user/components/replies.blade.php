<input type="hidden" data-total-replies="{{ $review_id }}" value="{{ $total_replies }}">

@foreach ($replies as $reply)
    <div class="review-item reply-item d-flex align-items-start mb-3">
        <div class="review-img reply-img me-2">
            <img src="{{ asset($reply->user->profile_picture ?: 'assets/images/default-avatar.jpg') }}"
                class="rounded-circle" width="40" height="40" alt="{{ $reply->user->name }}">
        </div>
        <div class="review-text reply-text">
            <div class="r-title reply-title d-flex align-items-center">
                <h3 class="fs-6 mb-0 fw-bold">{{ $reply->user->name }}</h3>
                <span class="ms-2 text-muted small">{{ $reply->created_at->format('d M Y') }}</span>
            </div>
            <p class="small mt-2">{{ $reply->comment }}</p>
            @if (auth()->id() == $reply->user_id)
                <form action="{{ route('comment.reply.destroy', $reply->id) }}" method="POST" class="d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn btn-danger btn-sm d-inline-flex align-items-center gap-1"
                        onclick="return confirm('{{ __('main.hapus_balasan_ini') }}')">
                        <iconify-icon icon="solar:trash-bin-trash-linear" style="font-size: 18px;"></iconify-icon>
                        {{ __('main.hapus') }}
                    </button>
                </form>
            @endif
        </div>
    </div>
@endforeach
