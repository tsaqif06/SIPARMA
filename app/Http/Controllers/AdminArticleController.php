<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminArticleController extends Controller
{
    // Tampilkan semua artikel
    public function index()
    {
        $articles = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->latest()
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    // Tampilkan artikel berdasarkan ID
    public function show($id)
    {
        $article = Article::with([
            'comments' => function ($query) {
                $query->whereNull('parent_id')
                    ->with('replies.user')
                    ->orderBy('created_at', 'asc');
            },
            'comments.user'
        ])->findOrFail($id);

        return view('admin.articles.show', compact('article'));
    }


    // Form tambah artikel baru
    public function create()
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.create', compact('categories'));
    }

    // Simpan artikel baru
    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'category_id' => 'required|exists:tbl_article_categories,id',
            'status'      => 'required|in:draft,published',
            'tags'        => 'string|max:100',
            'thumbnail'   => 'nullable|image|max:4096'
        ]);

        DB::beginTransaction();

        // Simpan thumbnail jika ada
        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('article/thumbnail', 'public')
            : null;

        // Proses gambar dalam artikel
        $content = $this->processArticleImages($request->content);

        // Simpan artikel
        $article = Article::create([
            'user_id'     => auth()->user()->id,
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'content'     => $content,
            'thumbnail'   => $thumbnailPath ? 'storage/' . $thumbnailPath : null,
            'status'      => $request->status
        ]);

        $tagsArray = explode(',', $request->tags ?? '');
        $tagsArray = array_map('trim', $tagsArray);
        $tagsArray = array_filter($tagsArray);

        // Simpan tags
        $this->syncTags($article, $tagsArray);


        DB::commit();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil ditambahkan');
    }

    // Upload gambar ke storage dari Trix
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $path = 'article/content/' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        Storage::disk('public')->put($path, file_get_contents($request->file('image')));

        return response()->json([
            'url' => Storage::url($path)
        ]);
    }


    // Update artikel
    public function edit(Article $article)
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'content'     => 'required',
            'category_id' => 'required|exists:tbl_article_categories,id',
            'status'      => 'required|in:draft,published',
            'tags'        => 'string|max:100',
            'thumbnail'   => 'nullable|image|max:4096'
        ]);

        DB::beginTransaction();

        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $article->content, $oldImages);
        $oldImages = $oldImages[1] ?? [];

        $content = $this->processArticleImages($request->content);

        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $content, $newImages);
        $newImages = $newImages[1] ?? [];

        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete("article/content/" . $image);
        }

        if ($request->hasFile('thumbnail')) {
            if ($article->thumbnail) {
                Storage::delete(str_replace('storage/', 'public/', $article->thumbnail));
            }
            $thumbnailPath = $request->file('thumbnail')->store('article/thumbnail', 'public');
            $article->thumbnail = 'storage/' . $thumbnailPath;
        }

        $article->update([
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'category_id' => $request->category_id,
            'content'     => $content,
            'status'      => $request->status,
            'thumbnail'   => $article->thumbnail ?? $article->getOriginal('thumbnail')
        ]);

        $tagsArray = explode(',', $request->tags ?? '');
        $tagsArray = array_map('trim', $tagsArray);
        $tagsArray = array_filter($tagsArray);
        $this->syncTags($article, $tagsArray);

        DB::commit();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diperbarui');
    }

    // Hapus artikel
    public function destroy(Article $article)
    {
        DB::beginTransaction();

        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $article->content, $images);
        $images = $images[1] ?? [];

        foreach ($images as $image) {
            Storage::disk('public')->delete("article/content/" . $image);
        }

        if ($article->thumbnail) {
            $thumbnailPath = str_replace('storage/', '', $article->thumbnail);
            Storage::disk('public')->delete($thumbnailPath);
        }

        $article->delete();

        DB::commit();

        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil dihapus');
    }


    // Proses gambar base64 dalam konten
    private function processArticleImages($content)
    {
        $dom = new DOMDocument();
        @$dom->loadHTML($content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        foreach ($dom->getElementsByTagName('img') as $img) {
            $src = $img->getAttribute('src');

            if (strpos($src, 'data:image') === 0) {
                $imageData = base64_decode(explode(',', $src)[1]);
                $imageName = 'article/content/' . uniqid() . '.png';

                Storage::disk('public')->put($imageName, $imageData);
                $img->setAttribute('src', asset('storage/' . $imageName));
            }
        }

        return $dom->saveHTML();
    }

    // Kelola tags artikel
    private function syncTags(Article $article, array $tags)
    {
        DB::table('tbl_article_tags')->where('article_id', $article->id)->delete();

        foreach ($tags as $tagName) {
            DB::table('tbl_article_tags')->insert([
                'article_id' => $article->id,
                'tag_name'   => $tagName,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
