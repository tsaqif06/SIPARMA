<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Place;
use App\Models\AdminPlace;
use App\Models\Destination;
use App\Models\Transaction;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        return view('user.profile.index');
    }

    public function profileUpdate(Request $request)
    {
        $user = User::findOrFail(auth()->user()->id);

        $emailValidation = 'required|email|max:100';
        if ($request->email !== $user->email) {
            $emailValidation .= '|unique:tbl_users,email';
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => $emailValidation,
            'phone_number' => 'nullable|string|max:15',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
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
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? $user->phone_number,
            'profile_picture' => $picturePath,
        ]);

        Auth::setUser($user);

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function transactionHistory()
    {
        $user = auth()->user();

        $transactions = Transaction::where('user_id', $user->id)
        ->orderBy('created_at', 'desc')
        ->paginate(5);

        return view('user.profile.riwayat', compact('transactions'));
    }

    public function adminPlaceVerification()
    {
        $destinations = Destination::all();
        return view('user.profile.adminverification', compact('destinations'));
    }

    public function storeVerification(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|max:100',
            'address' => 'required|string',
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'ownership_docs' => 'required|file|mimes:pdf,jpg,png,jpeg|max:5000',
        ]);

        // Simpan dokumen kepemilikan
        $docPath = $request->file('ownership_docs')->store('ownership_docs', 'public');

        $location = [
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ];

        // Simpan data tempat
        $place = Place::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'description' => '',
            'open_time' => '00:00:00',
            'close_time' => '23:59:59',
            'operational_status' => 'closed',
            'location' => json_encode($location),
            'type' => $request->type,
            'destination_id' => $request->destination_id,
        ]);

        // Simpan data admin tempat
        AdminPlace::create([
            'user_id' => auth()->user()->id,
            'place_id' => $place->id,
            'approval_status' => 'pending',
            'ownership_docs' => "storage/$docPath"
        ]);

        return redirect()->back()->with('success', 'Pengajuan verifikasi berhasil dikirim.');
    }
}
