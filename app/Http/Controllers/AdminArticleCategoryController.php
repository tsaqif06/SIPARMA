<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Controller untuk manajemen kategori artikel di panel admin.
 * Hanya dapat diakses oleh pengguna dengan peran 'superadmin'.
 */
class AdminArticleCategoryController extends Controller
{
    /**
     * Konstruktor: Menambahkan middleware untuk autentikasi dan otorisasi.
     * Pengguna selain 'superadmin' akan diarahkan kembali ke dashboard admin.
     */
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
     * Menampilkan daftar semua kategori artikel.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $categories = ArticleCategory::with('articles')->get();
        return view('admin.articles.category.index', compact('categories'));
    }

    /**
     * Menampilkan form untuk menambahkan kategori baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.articles.category.create');
    }

    /**
     * Menyimpan data kategori artikel baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Menampilkan form edit untuk kategori tertentu.
     *
     * @param \App\Models\ArticleCategory $category
     * @return \Illuminate\View\View
     */
    public function edit(ArticleCategory $category)
    {
        return view('admin.articles.category.edit', compact('category'));
    }

    /**
     * Memperbarui data kategori di database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\ArticleCategory $category
     * @return \Illuminate\Http\RedirectResponse
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
     * Menghapus kategori artikel dari database.
     *
     * @param \App\Models\ArticleCategory $category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(ArticleCategory $category)
    {
        $category->delete();
        return redirect()->route('admin.articles.category.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
