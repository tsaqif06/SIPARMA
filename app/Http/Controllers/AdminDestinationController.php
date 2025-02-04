<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.destinations.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }

        $destinations = Destination::all();

        foreach ($destinations as $destination) {
         $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($destination->operational_status === 'holiday') {
                $destination->status = 'Libur';
            } else {
                $destination->status = ($current_time >= $destination->open_time && $current_time <= $destination->close_time)
                    ? 'Buka'
                    : 'Tutup';
            }
        }

        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.destinations.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }

        return view('admin.destinations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:alam,wahana',
            'location' => 'required|string|max:255',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Destination::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'location' => $validated['location'],
        ]);

        return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminDestinations->contains('destination_id', $destination->id)) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($destination->operational_status === 'holiday') {
                $destination->status = 'Libur';
            } else {
                $destination->status = ($current_time >= $destination->open_time && $current_time <= $destination->close_time)
                    ? 'Buka'
                    : 'Tutup';
            }

            return view('admin.destinations.show', compact('destination'));
        }

        return redirect()->route('admin.destinations.show', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminDestinations->contains('destination_id', $destination->id)) {
            return view('admin.destinations.edit', compact('destination'));
        }

        return redirect()->route('admin.destinations.edit', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:alam,wahana',
            'location' => 'required|string|max:255',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'operational_status' => 'nullable|in:open,holiday',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $destination->update($validated);

        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah diupdate.');
        } else {
            return redirect()->route('admin.destinations.show', $destination->id)
                ->with('success', 'Wisata telah diupdate.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        foreach ($destination->gallery as $image) {
            $imagePath = public_path($image->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah dihapus.');
    }
}
