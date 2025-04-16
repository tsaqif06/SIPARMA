<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\ArticleView;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola artikel, termasuk menampilkan artikel, pencarian, komentar, balasan,
 * dan interaksi pengguna (like, views). Fungsionalitas lainnya termasuk filter kata-kata kasar
 * dalam komentar dan pengelolaan artikel terkait.
 */
class ArticleController extends Controller
{
    /**
     * Tampilkan semua artikel berdasarkan query pencarian.
     * Menyediakan fungsi pencarian berdasarkan judul, konten, kategori, dan tag.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function browse(Request $request)
    {
        $query = $request->input('search');
        $sort = $request->input('sort', 'populer'); // default 'populer'

        $articles = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->where('status', 'published')
            ->when($query, function ($q) use ($query) {
                $q->where('title', 'LIKE', "%{$query}%")
                    ->orWhere('content', 'LIKE', "%{$query}%")
                    ->orWhereHas('category', function ($q) use ($query) {
                        $q->where('name', 'LIKE', "%{$query}%");
                    })
                    ->orWhereHas('tags', function ($q) use ($query) {
                        $q->where('tag_name', 'LIKE', "%{$query}%");
                    });
            })
            ->when($sort === 'populer', function ($q) {
                $q->withCount('views')->orderBy('views_count', 'desc');
            })
            ->when($sort === 'terbaru', function ($q) {
                $q->orderBy('created_at', 'desc');
            })
            ->when($sort === 'like_terbanyak', function ($q) {
                $q->withCount('likes')->orderBy('likes_count', 'desc');
            })
            ->when($sort === 'komen_terbanyak', function ($q) {
                $q->withCount('comments')->orderBy('comments_count', 'desc');
            })
            ->paginate(9);

        return view('user.articles.browse', compact('articles'));
    }

    /**
     * Tampilkan artikel berdasarkan slug dan tambah view.
     * Fungsi ini juga menampilkan artikel terkait berdasarkan kategori dan tag.
     *
     * @param string $slug
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function show($slug, Request $request)
    {
        $article = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->where('slug', $slug)
            ->firstOrFail();

        $relatedByCategory = Article::where('category_id', $article->category_id)
            ->where('id', '!=', $article->id)
            ->limit(5);

        $tagIds = $article->tags->pluck('tag_name');

        if ($tagIds->isNotEmpty()) {
            $relatedByTags = Article::where('id', '!=', $article->id)
                ->whereHas('tags', function ($query) use ($tagIds) {
                    $query->whereIn('tag_name', $tagIds);
                })
                ->limit(5);

            $relatedArticles = $relatedByCategory->union($relatedByTags)->inRandomOrder()->limit(5)->get();
        } else {
            $relatedArticles = $relatedByCategory->inRandomOrder()->get();
        }

        $this->addView($article->id, $request->ip());

        $reviews = $article->comments()
            ->whereNull('parent_id')
            ->with(['user:id,name,profile_picture', 'replies' => function ($query) {
                $query->with('user:id,name,profile_picture')
                    ->latest()
                    ->limit(3);
            }])
            ->withCount('replies')
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('user.articles.show', compact('article', 'reviews', 'relatedArticles'));
    }

    /**
     * Fungsi untuk menambahkan view pada artikel dan memastikan satu IP hanya bisa menambah satu view.
     *
     * @param int $articleId
     * @param string $ip
     * @return void
     */
    private function addView($articleId, $ip)
    {
        $existingView = ArticleView::where('article_id', $articleId)->where('ip_address', $ip)->first();

        if (!$existingView) {
            ArticleView::create([
                'article_id' => $articleId,
                'ip_address' => $ip,
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
        }
    }

    /**
     * Menyukai atau tidak menyukai artikel berdasarkan request.
     * Fungsi ini akan mengubah status like artikel dan menghitung ulang jumlah like.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function toggleLike(Request $request)
    {
        $articleId = $request->article_id;
        $userId = auth()->id();

        $existingLike = ArticleLike::where('article_id', $articleId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            $existingLike->delete();
            $status = 'unliked';
        } else {
            ArticleLike::create([
                'article_id' => $articleId,
                'user_id' => $userId
            ]);
            $status = 'liked';
        }

        $likeCount = ArticleLike::where('article_id', $articleId)->count();

        return response()->json([
            'status' => $status,
            'likes' => $likeCount
        ]);
    }

    /**
     * Filter kata-kata kasar dalam teks komentar.
     * Menggantikan kata-kata kasar dengan tanda bintang `***`.
     *
     * @param string $text
     * @return string
     */
    public function filterBadWords($text)
    {
        $badWords = [
            'anjing',
            'babi',
            'bangsat',
            'brengsek',
            'goblok',
            'tolol',
            'idiot',
            'kampret',
            'kontol',
            'memek',
            'ngentot',
            'pepek',
            'peler',
            'pler',
            'setan',
            'sialan',
            'tai',
            'jembut',
            'kenthu',
            'keparat',
            'asu',
            'bencong',
            'banci',
            'lonte',
            'pelacur',
            'sundal',
            'perek',
            'bego',
            'dongo',
            'dungu',
            'gembel',
            'laknat',
            'mampus',
            'mati lu',
            'kuburan lu',
            'kimak',
            'fuckboy',
            'fuckgirl',
            'fuck',
            'shit',
            'asshole',
            'bitch',
            'bastard',
            'dick',
            'cunt',
            'motherfucker',
            'pussy',
            'slut',
            'whore',
            'dumbass',
            'retard',
            'moron',
            'stupid',
            'jerk',
            'prick',
            'wanker',
            'bollocks',
            'twat',
            'bloody hell',
            'dipshit',
            'faggot',
            'cock',
            'scumbag',
            'nigger',
            'nigga',
            'hoe',
            'jackass',
            'douchebag'
        ];

        return str_ireplace($badWords, '***', $text);
    }

    /**
     * Simpan komentar baru pada artikel.
     * Menggunakan filter bad words untuk memastikan komentar bersih.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $cleanComment = $this->filterBadWords($request->comment);

        ArticleComment::create([
            'article_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $cleanComment,
            'parent_id' => null,
        ]);

        return back()->with('success', __('flasher.komentar_ditambahkan'));
    }

    /**
     * Simpan balasan komentar pada artikel.
     * Balasan juga difilter untuk kata-kata kasar.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $cleanComment = $this->filterBadWords($request->comment);

        $parentComment = ArticleComment::findOrFail($id);

        ArticleComment::create([
            'article_id' => $parentComment->article_id,
            'user_id' => Auth::id(),
            'comment' => $cleanComment,
            'parent_id' => $id,
        ]);

        return back()->with('success', __('flasher.balasan_dikirim'));
    }

    /**
     * Hapus komentar beserta balasan yang terkait.
     * Hanya pengguna yang memiliki komentar dapat menghapusnya.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function commentDestroy($id)
    {
        $comment = ArticleComment::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized');
        }

        $comment->replies()->delete();
        $comment->delete();

        return back()->with('success', __('flasher.komentar_dihapus'));
    }

    /**
     * Hapus balasan komentar.
     * Hanya pengguna yang membuat balasan yang dapat menghapusnya.
     *
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function replyDestroy($id)
    {
        $reply = ArticleComment::findOrFail($id);

        if (Auth::id() !== $reply->user_id) {
            abort(403, 'Unauthorized');
        }

        $reply->delete();

        return back()->with('success', __('flasher.balasan_dihapus'));
    }
}
