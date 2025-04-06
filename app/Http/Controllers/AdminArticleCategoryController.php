<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminArticleCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'superadmin') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }
    /**
     * Tampilkan daftar kategori
     */
    public function index()
    {
        $categories = ArticleCategory::with('articles')->get();
        return view('admin.articles.category.index', compact('categories'));
    }

    /**
     * Tampilkan form tambah kategori
     */
    public function create()
    {
        return view('admin.articles.category.create');
    }

    /**
     * Simpan kategori baru
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tbl_article_categories,name'
        ]);

        ArticleCategory::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.articles.category.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori
     */
    public function edit(ArticleCategory $category)
    {
        return view('admin.articles.category.edit', compact('category'));
    }

    /**
     * Update kategori
     */
    public function update(Request $request, ArticleCategory $category)
    {
        $request->validate([
            'name' => 'required|string|max:100|unique:tbl_article_categories,name,' . $category->id
        ]);

        $category->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('admin.articles.category.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Hapus kategori
     */
    public function destroy(ArticleCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.articles.category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
