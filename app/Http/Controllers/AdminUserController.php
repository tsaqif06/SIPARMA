<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Place;
use App\Models\AdminPlace;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\AdminDestination;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with(['adminDestinations', 'adminPlaces'])->get();

        $filteredUsers = [];
        foreach ($users as $user) {
            $keep = true;
            foreach ($user->adminPlaces as $place) {
                if ($place->approval_status !== 'approved') {
                    $keep = false;
                    break;
                }
            }
            if ($keep) {
                $filteredUsers[] = $user;
            }
        }

        return view('admin.users.index', ['users' => $filteredUsers]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $managedItems = null;
        $destinations = null;
        $places = null;

        // if ($user->role === 'admin_wisata') {
        //     $managedItems = AdminDestination::where('user_id', $user->id)->get();
        //     $destinations = Destination::whereNotIn('id', AdminDestination::pluck('destination_id'))->get();
        // } elseif ($user->role === 'admin_tempat') {
        //     $managedItems = AdminPlace::where('user_id', $user->id)->get();
        //     $places = Place::whereNotIn('id', AdminPlace::pluck('place_id'))->get();
        // }

        // Kirim data ke view
        return view('admin.users.create', compact('user', 'managedItems', 'destinations', 'places'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        User::create($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User telah berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        if (!$user) {
            $message = 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => $message
            ], 404);
        }

        if ($user->adminPlace && $user->adminPlace->approval_status !== 'approved') {
            $message = 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => $message
            ], 404);
        }

        $managedItems = null;

        if ($user->role === 'admin_wisata') {
            $managedItems = AdminDestination::with('destination.gallery')
                ->where('user_id', $user->id)
                ->first();
        } elseif ($user->role === 'admin_tempat') {
            $managedItems = AdminPlace::with('place.gallery')
                ->where('user_id', $user->id)
                ->first();
        }

        return view('admin.users.show', compact('user', 'managedItems'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        if (!$user) {
            $message = 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => $message
            ], 404);
        }

        if ($user->adminPlace && $user->adminPlace->approval_status !== 'approved') {
            $message = 'Halaman yang Anda cari tidak ditemukan.';
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => $message
            ], 404);
        }

        $managedItems = null;
        $destinations = null;
        $places = null;

        if ($user->role === 'admin_wisata') {
            $managedItems = AdminDestination::where('user_id', $user->id)->first();

            $destinationsWithoutAdmin = Destination::whereNotIn('id', AdminDestination::pluck('destination_id')->toArray())->pluck('id')->toArray();
            $destinations = Destination::whereIn('id', array_merge([$managedItems->destination_id], $destinationsWithoutAdmin))->get();
        } elseif ($user->role === 'admin_tempat') {
            $managedItems = AdminPlace::where('user_id', $user->id)->first();

            $placesWithoutAdmin = Place::whereNotIn('id', AdminPlace::pluck('place_id')->toArray())->pluck('id')->toArray();
            $places = Place::whereIn('id', array_merge([$managedItems->place_id], $placesWithoutAdmin))->get();
        }

        return view('admin.users.edit', compact('user', 'managedItems', 'destinations', 'places'));
    }




    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'role' => 'required',
            'admin_destinations' => 'nullable|exists:destinations,id',
            'admin_places' => 'nullable|exists:places,id',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $request->role,
        ]);

        if ($user->role === 'admin_wisata' && $request->has('admin_destinations')) {
            $user->adminDestination()->associate($request->admin_destinations);
        }

        if ($user->role === 'admin_tempat' && $request->has('admin_places')) {
            $user->adminPlace()->associate($request->admin_places);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User telah berhasil dihapus.');
    }
}
