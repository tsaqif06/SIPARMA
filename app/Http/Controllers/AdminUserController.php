<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AdminPlace;
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
        return view('admin.users.create');
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
        $managedItems = null;

        // Cek role user dan ambil data sesuai role
        if ($user->role === 'admin_wisata') {
            $managedItems = AdminDestination::with('destination.gallery')
                // ->where('user_id', 2)
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($item) {
                    return $item->destination;
                });
        } elseif ($user->role === 'admin_tempat') {
            $managedItems = AdminPlace::with('place.gallery')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($item) {
                    return $item->place;
                });
        }

        return view('admin.users.show', compact('user', 'managedItems'));
    }

    public function showUserDetails($id)
    {
        $user = User::findOrFail($id);

        $managedItems = null;

        // Cek role user dan ambil data sesuai role
        if ($user->role === 'admin_wisata') {
            $managedItems = AdminDestination::with('destination')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($item) {
                    return $item->destination;
                });
        } elseif ($user->role === 'admin_tempat') {
            $managedItems = AdminPlace::with('place')
                ->where('user_id', $user->id)
                ->get()
                ->map(function ($item) {
                    return $item->place;
                });
        }

        return view('users.details', compact('user', 'managedItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
        ]);

        $user->update($request->all());

        return redirect()->route('admin.users.index')->with('success', 'User telah berhasil diupdate.');
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
