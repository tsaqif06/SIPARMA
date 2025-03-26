<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleLike;
use App\Models\ArticleView;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleComment;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    // Tampilkan semua artikel
    public function browse(Request $request)
    {
        $query = $request->input('search');

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
            ->latest()
            ->paginate(9);

        return view('user.articles.browse', compact('articles'));
    }

    // Tampilkan satu artikel (plus tambahin views)
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

    // Fungsi menambahkan views (anti-spam)
    private function addView($articleId, $ip)
    {
        // Cek apakah IP sudah pernah nambah view ke artikel ini
        $existingView = ArticleView::where('article_id', $articleId)->where('ip_address', $ip)->first();

        if (!$existingView) {
            ArticleView::create([
                'article_id' => $articleId,
                'ip_address' => $ip,
                'user_id' => Auth::check() ? Auth::id() : null
            ]);
        }
    }

    // INTERAKSI

    public function toggleLike(Request $request)
    {
        $articleId = $request->article_id;
        $userId = auth()->id();

        // Cek apakah user sudah like
        $existingLike = ArticleLike::where('article_id', $articleId)
            ->where('user_id', $userId)
            ->first();

        if ($existingLike) {
            // Jika sudah like, maka unlike
            $existingLike->delete();
            $status = 'unliked';
        } else {
            // Jika belum like, maka tambahkan
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

    // Simpan Komentar
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

    // Simpan Balasan Komentar
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

    // Hapus Komentar (termasuk balasan)
    public function commentDestroy($id)
    {
        $comment = ArticleComment::findOrFail($id);

        if (Auth::id() !== $comment->user_id) {
            abort(403, 'Unauthorized');
        }

        // Hapus semua balasan juga
        $comment->replies()->delete();
        $comment->delete();

        return back()->with('success', __('flasher.komentar_dihapus'));
    }

    // Hapus Balasan
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
