<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Place;
use App\Models\AdminPlace;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class AdminPlaceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            if (Auth::user()->role === 'admin_tempat') {
                return redirect()->route('admin.places.show', auth()->user()->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
            }
            return redirect()->route('admin.destinations.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }

        $places = Place::whereHas('admin', function ($query) {
            $query->where('approval_status', 'approved'); // Contoh: Filter berdasarkan status
        })->get();

        foreach ($places as $place) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            $place->status = ($current_time >= $place->open_time && $current_time <= $place->close_time)
                ? 'Buka'
                : 'Tutup';
        }

        return view('admin.places.index', compact('places'));
    }

    public function approval()
    {
        $adminplaces = AdminPlace::where('approval_status', 'pending')->get();

        return view('admin.places.approval', compact('adminplaces'));
    }

    public function getPlace($id)
    {
        $place = AdminPlace::with('user', 'place.destination')->findOrFail($id);

        return response()->json($place);
    }

    public function updateStatus(Request $request, AdminPlace $adminplace)
    {
        $adminplace->approval_status = $request->status;
        $adminplace->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.places.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }

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
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'destination_id' => 'required|exists:tbl_destinations,id',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $location = [
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ];

        Place::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'location' => json_encode($location),
            'destination_id' => $validated['destination_id'],
        ]);

        return redirect()->route('admin.places.index')->with('success', 'Tempat telah ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Place $place)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminPlaces->contains('place_id', $place->id)) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            $place->status = ($current_time >= $place->open_time && $current_time <= $place->close_time)
                ? 'Buka'
                : 'Tutup';

            return view('admin.places.show', compact('place'));
        }

        if (Auth::user()->role === 'admin_place') {
            return redirect()->route('admin.places.show', $user->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
        }
        return redirect()->route('admin.destinations.show', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Place $place)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminPlaces->contains('place_id', $place->id)) {
            $destinations = Destination::all();
            return view('admin.places.edit', compact('place', 'destinations'));
        }

        if (Auth::user()->role === 'admin_place') {
            return redirect()->route('admin.places.edit', $user->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
        }
        return redirect()->route('admin.destinations.edit', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Place $place)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'destination_id' => 'required|exists:tbl_destinations,id',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'operational_status' => 'nullable|in:open,closed',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $validated['location'] = json_encode([
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        $place->update($validated);

        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('admin.places.index')->with('success', 'Tempat telah diupdate.');
        } else {
            return redirect()->route('admin.places.show', $place->id)
                ->with('success', 'Tempat telah diupdate.');
        }
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
