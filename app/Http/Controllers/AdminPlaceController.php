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

/**
 * Controller untuk mengelola tempat (Place) dari sisi admin.
 */
class AdminPlaceController extends Controller
{
    /**
     * Menampilkan daftar tempat yang telah disetujui.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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

    /**
     * Menampilkan halaman approval tempat oleh superadmin.
     *
     * @return \Illuminate\View\View
     */
    public function approval()
    {
        $adminplaces = AdminPlace::where('approval_status', 'pending')->get();

        return view('admin.places.approval', compact('adminplaces'));
    }

    /**
     * Mengambil data AdminPlace lengkap dengan relasi user dan destination.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getPlace($id)
    {
        $place = AdminPlace::with('user', 'place.destination')->findOrFail($id);

        return response()->json($place);
    }

    /**
     * Mengubah status approval untuk AdminPlace.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\AdminPlace $adminplace
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStatus(Request $request, AdminPlace $adminplace)
    {
        $adminplace->approval_status = $request->status;
        $adminplace->save();

        return redirect()->back()->with('success', 'Status berhasil diperbarui!');
    }

    /**
     * Menampilkan form pembuatan tempat baru (khusus superadmin).
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
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
     * Menyimpan data tempat baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Menampilkan detail tempat berdasarkan hak akses user.
     *
     * @param \App\Models\Place $place
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show(Place $place)
    {
        $user = auth()->user();
        $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if ($user->role === 'superadmin') {
            // Superadmin bisa akses semua tempat
            $place->status = ($current_time >= $place->open_time && $current_time <= $place->close_time)
                ? 'Buka'
                : 'Tutup';

            return view('admin.places.show', compact('place'));
        }

        if ($user->role === 'admin_tempat') {
            // Cek apakah tempat yang diakses ini sudah di-approve
            $adminPlace = AdminPlace::where('user_id', $user->id)
                ->where('place_id', $place->id)
                ->first();

            if (!$adminPlace || $adminPlace->approval_status !== 'approved') {
                // Cari tempat terbaru yang approved
                $approvedPlace = AdminPlace::where('user_id', $user->id)
                    ->where('approval_status', 'approved')
                    ->latest('created_at')
                    ->first();

                if ($approvedPlace) {
                    return redirect()->route('admin.places.show', $approvedPlace->place_id)
                        ->with('error', 'Tempat ini belum disetujui, Anda diarahkan ke tempat yang telah disetujui.');
                }

                return redirect()->route('admin.dashboard')
                    ->with('error', 'Tidak ada tempat yang disetujui.');
            }
        }

        if ($user->adminPlaces->contains('place_id', $place->id)) {
            // Cek status buka/tutup
            $place->status = ($current_time >= $place->open_time && $current_time <= $place->close_time)
                ? 'Buka'
                : 'Tutup';

            return view('admin.places.show', compact('place'));
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Akses ditolak!');
    }

    /**
     * Menampilkan form edit tempat (berdasarkan role dan akses).
     *
     * @param \App\Models\Place $place
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(Place $place)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin') {
            // Superadmin bisa edit semua tempat
            $destinations = Destination::all();
            return view('admin.places.edit', compact('place', 'destinations'));
        }

        if ($user->role === 'admin_tempat') {
            // Cek apakah tempat yang diakses ini sudah di-approve
            $adminPlace = AdminPlace::where('user_id', $user->id)
                ->where('place_id', $place->id)
                ->first();

            if (!$adminPlace || $adminPlace->approval_status !== 'approved') {
                // Cari tempat terbaru yang approved
                $approvedPlace = AdminPlace::where('user_id', $user->id)
                    ->where('approval_status', 'approved')
                    ->latest('created_at')
                    ->first();

                if ($approvedPlace) {
                    return redirect()->route('admin.places.edit', $approvedPlace->place_id)
                        ->with('error', 'Tempat ini belum disetujui, Anda diarahkan ke tempat yang telah disetujui.');
                }

                return redirect()->route('admin.dashboard')
                    ->with('error', 'Tidak ada tempat yang disetujui.');
            }
        }

        if ($user->adminPlaces->contains('place_id', $place->id)) {
            // Admin tempat bisa edit tempatnya sendiri (yang approved)
            $destinations = Destination::all();
            return view('admin.places.edit', compact('place', 'destinations'));
        }

        return redirect()->route('admin.dashboard')
            ->with('error', 'Akses ditolak!');
    }

    /**
     * Memperbarui data tempat.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Place $place
     * @return \Illuminate\Http\RedirectResponse
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
     * Menghapus data tempat beserta galeri terkait.
     *
     * @param \App\Models\Place $place
     * @return \Illuminate\Http\RedirectResponse
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
