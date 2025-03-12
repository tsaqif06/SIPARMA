<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleTag;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminArticleController extends Controller
{
    // Tampilkan semua artikel (termasuk draft & published)
    public function index()
    {
        $articles = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->latest()
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    // Tampilkan satu artikel berdasarkan ID
    public function show($id)
    {
        $article = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->findOrFail($id);

        return view('admin.articles.show', compact('articles'));
    }

    // Tambah artikel baru
    public function create()
    {
        $categories = ArticleCategory::all(); // Ambil semua kategori dari database
        return view('admin.articles.create', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'category_id' => 'required|exists:article_categories,id',
            'status'      => 'required|in:draft,published',
            'tags'        => 'array',
            'tags.*'      => 'string|max:100',
            'thumbnail'   => 'nullable|url'
        ]);

        $article = Article::create([
            'user_id'     => auth()->user()->id,
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'content'     => $request->content,
            'thumbnail'   => $request->thumbnail ?? null,
            'status'      => $request->status
        ]);

        // Simpan tags
        $this->syncTags($article, $request->tags ?? []);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan');
    }

    // Update artikel
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'category_id' => 'required|exists:article_categories,id',
            'status'      => 'required|in:draft,published',
            'tags'        => 'array',
            'tags.*'      => 'string|max:100',
            'thumbnail'   => 'nullable|url'
        ]);

        $article->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'content'     => $request->content,
            'category_id' => $request->category_id,
            'thumbnail'   => $request->thumbnail ?? $article->thumbnail,
            'status'      => $request->status
        ]);

        // Update tags
        $this->syncTags($article, $request->tags ?? []);

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui');
    }

    // Hapus artikel
    public function destroy($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus');
    }

    // Fungsi untuk mengelola tags (menambah dan menghapus yang tidak dipakai)
    private function syncTags(Article $article, array $tags)
    {
        $tagIds = [];
        foreach ($tags as $tagName) {
            $tag = ArticleTag::firstOrCreate(['name' => $tagName], ['slug' => Str::slug($tagName)]);
            $tagIds[] = $tag->id;
        }
        $article->tags()->sync($tagIds);
    }
}
