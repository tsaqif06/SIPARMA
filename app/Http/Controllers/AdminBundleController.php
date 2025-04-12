<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

/**
 * Controller untuk manajemen data bundle oleh admin wisata.
 */
class AdminBundleController extends Controller
{
    /**
     * Konstruktor controller.
     * Menerapkan middleware autentikasi dan membatasi akses hanya untuk admin_wisata.
     */
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin_wisata') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }

    /**
     * Menampilkan daftar bundle milik destinasi yang dikelola oleh admin saat ini.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $bundles = Bundle::with('items')
            ->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
            ->get();

        return view('admin.bundles.index', compact('bundles'));
    }

    /**
     * Menampilkan form untuk membuat bundle baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.bundles.create');
    }

    /**
     * Menyimpan data bundle baru ke database.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount'    => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['total_price'] = 0; // Di-set awal 0, nanti dihitung berdasarkan item
        $data['destination_id'] = auth()->user()->adminDestinations[0]->destination_id;

        Bundle::create($data);

        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil ditambahkan');
    }

    /**
     * Menampilkan form edit untuk bundle tertentu.
     *
     * @param Bundle $bundle
     * @return \Illuminate\View\View
     */
    public function edit(Bundle $bundle)
    {
        return view('admin.bundles.edit', compact('bundle'));
    }

    /**
     * Memperbarui data bundle di database.
     *
     * @param Request $request
     * @param Bundle $bundle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name'        => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount'    => 'nullable|numeric|min:0',
        ]);

        $bundle->update($request->all());

        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil diperbarui');
    }

    /**
     * Menghapus bundle dari database.
     *
     * @param Bundle $bundle
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bundle $bundle)
    {
        $bundle->delete();

        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil dihapus');
    }
}
