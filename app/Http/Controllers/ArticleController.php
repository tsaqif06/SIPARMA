<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use App\Models\ArticleView;
use Illuminate\Support\Str;
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

    // Tambah artikel
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
            'category_id' => 'required|exists:article_categories,id',
        ]);

        $article = Article::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'slug' => Str::slug($request->title),
            'content' => $request->content,
            'thumbnail' => $request->thumbnail,
            'status' => $request->status ?? 'draft'
        ]);

        return response()->json(['message' => 'Artikel berhasil dibuat', 'article' => $article]);
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
}
