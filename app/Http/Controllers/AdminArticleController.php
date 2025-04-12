<?php

namespace App\Http\Controllers;

use DOMDocument;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ArticleCategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk manajemen artikel di panel admin.
 */
class AdminArticleController extends Controller
{
    /**
     * Menampilkan semua artikel (khusus superadmin).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if (auth()->user()->role !== 'superadmin') {
            return redirect()->route('admin.articles.my')->with('error', 'Akses ditolak!');
        }

        $articles = Article::with(['user', 'category', 'tags', 'comments', 'likes', 'views'])
            ->where('status', '!=', 'blocked')
            ->latest()
            ->get();

        return view('admin.articles.index', compact('articles'));
    }

    /**
     * Menampilkan daftar artikel milik pengguna yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function my()
    {
        $articles = Article::with(['category', 'tags', 'comments', 'likes', 'views'])
            ->where('user_id', auth()->user()->id)
            ->latest()
            ->get();

        return view('admin.articles.my', compact('articles'));
    }

    /**
     * Memblokir artikel (status diubah menjadi 'blocked').
     *
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function block(Article $article)
    {
        $article->update(['status' => 'blocked']);
        return redirect()->route('admin.articles.index')->with('success', 'Artikel berhasil diblokir.');
    }

    /**
     * Menampilkan detail artikel berdasarkan ID.
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
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

    /**
     * Menampilkan form untuk membuat artikel baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = ArticleCategory::all();
        return view('admin.articles.create', compact('categories'));
    }

    /**
     * Menyimpan artikel baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

        $thumbnailPath = $request->hasFile('thumbnail')
            ? $request->file('thumbnail')->store('article/thumbnail', 'public')
            : null;

        $content = $this->processArticleImages($request->content);

        $article = Article::create([
            'user_id'     => auth()->user()->id,
            'category_id' => $request->category_id,
            'title'       => $request->title,
            'slug'        => Str::slug($request->title),
            'content'     => $content,
            'thumbnail'   => $thumbnailPath ? 'storage/' . $thumbnailPath : null,
            'status'      => $request->status
        ]);

        $tagsArray = array_filter(array_map('trim', explode(',', $request->tags ?? '')));
        $this->syncTags($article, $tagsArray);

        DB::commit();

        return redirect()->route('admin.articles.my')->with('success', 'Artikel berhasil ditambahkan');
    }

    /**
     * Mengunggah gambar dari editor Trix ke penyimpanan.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:2048'
        ]);

        $path = 'article/content/' . uniqid() . '.' . $request->file('image')->getClientOriginalExtension();
        Storage::disk('public')->put($path, file_get_contents($request->file('image')));

        return response()->json(['url' => Storage::url($path)]);
    }

    /**
     * Menampilkan form edit artikel.
     *
     * @param Article $article
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Article $article)
    {
        if (auth()->user()->id !== $article->user_id) {
            return redirect()->route('admin.articles.my')->with('error', 'Anda tidak memiliki izin untuk mengedit artikel ini.');
        }

        $categories = ArticleCategory::all();
        return view('admin.articles.edit', compact('article', 'categories'));
    }

    /**
     * Memperbarui data artikel.
     *
     * @param Request $request
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
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

        // Hapus gambar lama yang tidak digunakan
        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $article->content, $oldImages);
        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $request->content, $newImages);

        $oldImages = $oldImages[1] ?? [];
        $newImages = $newImages[1] ?? [];
        $imagesToDelete = array_diff($oldImages, $newImages);

        foreach ($imagesToDelete as $image) {
            Storage::disk('public')->delete("article/content/" . $image);
        }

        // Update thumbnail jika ada
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
            'content'     => $this->processArticleImages($request->content),
            'status'      => $request->status,
            'thumbnail'   => $article->thumbnail ?? $article->getOriginal('thumbnail')
        ]);

        $tagsArray = array_filter(array_map('trim', explode(',', $request->tags ?? '')));
        $this->syncTags($article, $tagsArray);

        DB::commit();

        return redirect()->route('admin.articles.my')->with('success', 'Artikel berhasil diperbarui');
    }

    /**
     * Menghapus artikel dan semua gambar terkait dari storage.
     *
     * @param Article $article
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Article $article)
    {
        DB::beginTransaction();

        preg_match_all('/src=["\']\/storage\/article\/content\/(.*?)["\']/', $article->content, $images);
        foreach ($images[1] ?? [] as $image) {
            Storage::disk('public')->delete("article/content/" . $image);
        }

        if ($article->thumbnail) {
            $thumbnailPath = str_replace('storage/', '', $article->thumbnail);
            Storage::disk('public')->delete($thumbnailPath);
        }

        $article->delete();

        DB::commit();

        return redirect()->route('admin.articles.my')->with('success', 'Artikel berhasil dihapus');
    }

    /**
     * Memproses gambar base64 dari konten artikel dan menyimpannya di storage.
     *
     * @param string $content
     * @return string
     */
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

    /**
     * Menyimpan tag yang terkait dengan artikel.
     *
     * @param Article $article
     * @param array $tags
     * @return void
     */
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
