<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\Promo;
use Illuminate\Support\Str;

class AdminPromoController extends Controller
{
    /**
     * Menampilkan daftar Promo berdasarkan destinasi.
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
     */
    public function create()
    {
        return view('admin.promo.create');
    }

    /**
     * Menyimpan data Promo baru.
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
     * Display the specified resource.
     */
    public function show(Promo $promo)
    {
        return view('admin.promo.show', compact('promo'));
    }

    /**
     * Menampilkan form untuk mengedit Promo.
     */
    public function edit(Promo $promo)
    {
        return view('admin.promo.edit', compact('promo'));
    }

    /**
     * Memperbarui data Promo.
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
     */
    public function destroy(Promo $promo)
    {
        $promo->delete();

        return redirect()->route('admin.promo.index')
            ->with('success', 'Promo berhasil dihapus.');
    }
}
