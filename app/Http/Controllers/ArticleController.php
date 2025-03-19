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
    public function browse()
    {
        $articles = Article::with(['category', 'tags', 'comments', 'likes', 'views'])->where('status', 'published')->latest()->get();
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

        $reviews = $article->comments()->paginate(5);


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

    // Simpan Komentar
    public function comment(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        ArticleComment::create([
            'article_id' => $id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'parent_id' => null, // Komentar utama
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    // Simpan Balasan Komentar
    public function reply(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        $parentComment = ArticleComment::findOrFail($id);

        ArticleComment::create([
            'article_id' => $parentComment->article_id,
            'user_id' => Auth::id(),
            'comment' => $request->comment,
            'parent_id' => $id, // Balasan ke komentar utama
        ]);

        return back()->with('success', 'Balasan berhasil dikirim!');
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

        return back()->with('success', 'Komentar berhasil dihapus!');
    }

    // Hapus Balasan
    public function replyDestroy($id)
    {
        $reply = ArticleComment::findOrFail($id);

        if (Auth::id() !== $reply->user_id) {
            abort(403, 'Unauthorized');
        }

        $reply->delete();

        return back()->with('success', 'Balasan berhasil dihapus!');
    }
}
