<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class AdminBundleController extends Controller
{
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

    public function index()
    {
        $bundles = Bundle::where('destination_id', auth()->user()->adminDestinations[0]->destination_id)->get();
        return view('admin.bundles.index', compact('bundles'));
    }

    public function create()
    {
        return view('admin.bundles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $data = $request->all();
        $data['total_price'] = 0;
        $data['destination_id'] = auth()->user()->adminDestinations[0]->destination_id;

        Bundle::create($data);

        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil ditambahkan');
    }

    public function edit(Bundle $bundle)
    {
        return view('admin.bundles.edit', compact('bundle'));
    }

    public function update(Request $request, Bundle $bundle)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'discount' => 'nullable|numeric|min:0',
        ]);

        $bundle->update($request->all());

        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil diperbarui');
    }

    public function destroy(Bundle $bundle)
    {
        $bundle->delete();
        return redirect()->route('admin.bundle.index')->with('success', 'Bundle berhasil dihapus');
    }
}
