<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Place;
use App\Models\AdminPlace;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\AdminDestination;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

/**
 * Controller untuk mengelola user oleh Superadmin.
 */
class AdminUserController extends Controller
{
    /**
     * Menampilkan daftar user berdasarkan role dan status approval tempat.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $users = User::with(['adminDestinations', 'adminPlaces'])
            ->orderByRaw("FIELD(role, 'superadmin', 'admin_wisata', 'admin_tempat', 'user')")
            ->get();

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
     * Menampilkan form pembuatan user baru (khusus superadmin).
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $destinations = Destination::whereNotIn('id', AdminDestination::pluck('destination_id'))->get();
        $places = Place::whereNotIn('id', AdminPlace::pluck('place_id'))->get();

        return view('admin.users.create', compact('destinations', 'places'));
    }

    /**
     * Menyimpan user baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:tbl_users,email|max:100',
            'password' => 'required|min:6',
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'phone_number' => 'required|string|max:30',
            'role' => 'required|string|in:admin_wisata,admin_tempat',
            'destination_id' => 'nullable|exists:tbl_destinations,id',
            'place_id' => 'nullable|exists:tbl_places,id',
        ]);

        $picturePath = null;

        if ($request->hasFile('profile_picture')) {
            $profilePicture = $request->file('profile_picture');
            $fileName = time() . '_' . $profilePicture->getClientOriginalName();
            $profilePicture->storeAs('public/profilepicture', $fileName);
            $picturePath = "storage/profilepicture/{$fileName}";
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'profile_picture' => $picturePath,
            'phone_number' => $validated['phone_number'],
            'role' => $validated['role'],
        ]);

        if ($validated['role'] === 'admin_wisata' && $request->destination_id) {
            AdminDestination::create([
                'user_id' => $user->id,
                'destination_id' => $request->destination_id,
            ]);
        } elseif ($validated['role'] === 'admin_tempat' && $request->place_id) {
            AdminPlace::create([
                'user_id' => $user->id,
                'place_id' => $request->place_id,
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'User telah ditambahkan');
    }

    /**
     * Menampilkan detail user tertentu, hanya bisa diakses oleh superadmin atau user itu sendiri.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function show(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role === 'superadmin' || $currentUser->id === $user->id) {
            $managedItems = null;

            if ($user->role === 'admin_wisata') {
                $managedItems = AdminDestination::with('destination.gallery')
                    ->where('user_id', $user->id)
                    ->first();
            } elseif ($user->role === 'admin_tempat') {
                $managedItems = AdminPlace::with('place.gallery')
                    ->where('user_id', $user->id)
                    ->first();

                if ($managedItems && $managedItems->approval_status !== 'approved') {
                    return response()->view('admin.error', [
                        'status_code' => 404,
                        'error' => 'Page Not Found',
                        'message' => 'Halaman yang Anda cari tidak ditemukan.'
                    ], 404);
                }
            }

            return view('admin.users.show', compact('user', 'managedItems'));
        }

        return response()->view('admin.error', [
            'status_code' => 404,
            'error' => 'Page Not Found',
            'message' => 'Halaman yang Anda cari tidak ditemukan.'
        ], 404);
    }

    /**
     * Menampilkan form edit user.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->role !== 'superadmin' && $currentUser->id !== $user->id) {
            return response()->view('admin.error', [
                'status_code' => 404,
                'error' => 'Page Not Found',
                'message' => 'Halaman yang Anda cari tidak ditemukan.'
            ], 404);
        }

        $managedItems = null;
        $destinations = null;
        $places = null;

        if ($user->role === 'admin_wisata') {
            $managedItems = AdminDestination::where('user_id', $user->id)->first();

            $destinationsWithoutAdmin = Destination::whereNotIn('id', AdminDestination::pluck('destination_id'))->pluck('id');
            $destinations = Destination::whereIn('id', $destinationsWithoutAdmin->push(optional($managedItems)->destination_id))->get();
        } elseif ($user->role === 'admin_tempat') {
            $managedItems = AdminPlace::where('user_id', $user->id)->first();

            $placesWithoutAdmin = Place::whereNotIn('id', AdminPlace::pluck('place_id'))->pluck('id');
            $places = Place::whereIn('id', $placesWithoutAdmin->push(optional($managedItems)->place_id))->get();
        }

        return view('admin.users.edit', compact('user', 'managedItems', 'destinations', 'places'));
    }

    /**
     * Memperbarui data user di database.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user)
    {
        $emailValidation = 'required|email|max:100';
        if ($request->email !== $user->email) {
            $emailValidation .= '|unique:tbl_users,email';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'email' => $emailValidation,
            'profile_picture' => 'nullable|image|mimes:jpg,jpeg,png|max:4096',
            'phone_number' => 'required|string|max:30',
            'destination_id' => 'nullable|exists:tbl_destinations,id',
            'place_id' => 'nullable|exists:tbl_places,id',
        ]);

        $picturePath = $user->profile_picture;

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                $oldFilePath = public_path($user->profile_picture);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

            $profilePicture = $request->file('profile_picture');

            $fileName = time() . '_' . $profilePicture->getClientOriginalName();

            $profilePicture->storeAs('public/profilepicture', $fileName);

            $picturePath = "storage/profilepicture/{$fileName}";
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'profile_picture' => $picturePath,
        ]);

        if ($user->role === 'admin_wisata' && $request->has('destination_id')) {
            $adminDestination = AdminDestination::where('user_id', $user->id)->first();

            if ($adminDestination) {
                $adminDestination->destination_id = $request->destination_id;
                $adminDestination->save();
            }
        }

        if ($user->role === 'admin_tempat' && $request->has('place_id')) {
            $adminPlace = AdminPlace::where('user_id', $user->id)->first();

            if ($adminPlace) {
                $adminPlace->place_id = $request->place_id;
                $adminPlace->save();
            }
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'User telah diupdate');
    }

    /**
     * Menghapus user dari database beserta relasinya jika ada.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user)
    {
        if ($user->profile_picture) {
            $oldFilePath = public_path($user->profile_picture);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        if ($user->role === 'admin_wisata') {
            AdminDestination::where('user_id', $user->id)->delete();
        } elseif ($user->role === 'admin_tempat') {
            AdminPlace::where('user_id', $user->id)->delete();
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User telah dihapus');
    }
}
