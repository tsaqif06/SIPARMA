<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Promo;
use Illuminate\Support\Str;

/**
 * Controller untuk mengelola data Promo dari sisi admin wisata.
 */
class AdminPromoController extends Controller
{
    /**
     * Membatasi akses hanya untuk user dengan role admin_wisata.
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
     * Menampilkan daftar Promo berdasarkan destinasi admin yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $promo = auth()->user()->adminDestinations[0]->destination->promos->map(function ($item) {
            $today = Carbon::today();

            if ($today->between($item->valid_from, $item->valid_until)) {
                $item->status = 'Berjalan';
            } else {
                $item->status = 'Tidak Berlaku';
            }

            return $item;
        });

        return view('admin.promo.index', compact('promo'));
    }

    /**
     * Menampilkan form untuk membuat Promo baru.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('admin.promo.create');
    }

    /**
     * Menyimpan data Promo baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',
            'place_id' => 'nullable|integer|exists:tbl_place,id',
            'discount' => 'required|integer',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date',
        ]);

        Promo::create($request->all());

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dari promo tertentu.
     *
     * @param \App\Models\Promo $promo
     * @return \Illuminate\View\View
     */
    public function show(Promo $promo)
    {
        return view('admin.promo.show', compact('promo'));
    }

    /**
     * Menampilkan form untuk mengedit Promo.
     *
     * @param \App\Models\Promo $promo
     * @return \Illuminate\View\View
     */
    public function edit(Promo $promo)
    {
        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Memperbarui data Promo di database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Promo $promo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Promo $promo)
    {
        $request->validate([
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',
            'place_id' => 'nullable|integer|exists:tbl_place,id',
            'discount' => 'required|integer',
            'valid_from' => 'required|date',
            'valid_until' => 'required|date',
        ]);

        $promo->update($request->all());

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil diperbarui.');
    }

    /**
     * Menghapus Promo dari database.
     *
     * @param \App\Models\Promo $promo
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}
