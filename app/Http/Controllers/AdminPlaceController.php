<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Place;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class AdminPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $places = Place::all();
        foreach ($places as $place) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            $place->status = ($current_time >= $place->open_time && $current_time <= $place->close_time)
                ? 'Buka'
                : 'Tutup';
        }

        return view('admin.places.index', compact('places'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $destinations = Destination::all();

        return view('admin.places.create', compact('destinations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'destination_id' => 'required|exists:tbl_destinations,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Place::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'location' => $validated['location'],
            'destination_id' => $validated['destination_id'],
        ]);

        return redirect()->route('admin.places.index')->with('success', 'Tempat telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        return view('admin.places.show', compact('place'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        $destinations = Destination::all();

        return view('admin.places.edit', compact('place', 'destinations'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'location' => 'required|string|max:255',
            'destination_id' => 'required|exists:tbl_destinations,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $place->update($validated);

        return redirect()->route('admin.places.index')->with('success', 'Tempat telah diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Place $place)
    {
        foreach ($place->gallery as $image) {
            $imagePath = public_path($image->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $place->delete();

        return redirect()->route('admin.places.index')->with('success', 'Tempat telah dihapus.');
    }
}
