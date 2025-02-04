<?php

namespace App\Http\Controllers;

use App\Models\Bundle;
use Illuminate\Http\Request;

class AdminBundleController extends Controller
{
    public function index()
    {
        $bundles = Bundle::all();
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
            'total_price' => 'required|numeric|min:0',
            'discount' => 'nullable|numeric|min:0',
        ]);

        Bundle::create($request->all());

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
            'total_price' => 'required|numeric|min:0',
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
