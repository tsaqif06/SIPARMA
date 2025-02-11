<?php

namespace App\Http\Controllers;

use App\Models\User;
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
}
