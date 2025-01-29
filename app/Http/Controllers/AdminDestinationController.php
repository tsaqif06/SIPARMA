<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AdminDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $destinations = Destination::all();

        foreach ($destinations as $destination) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($destination->operational_status === 'holiday') {
                $destination->status = 'Holiday';
            } else {
                $destination->status = ($current_time >= $destination->open_time && $current_time <= $destination->close_time)
                    ? 'Open'
                    : 'Closed';
            }
        }

        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        return view('admin.destinations.show', compact('destination'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        return view('admin.destinations.edit', compact('destination'));
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
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $destination->update($validated);

        return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah diupdate.');
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
