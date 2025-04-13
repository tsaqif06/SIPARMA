<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Place;
use App\Models\AdminPlace;
use App\Models\Destination;
use App\Models\Transaction;
use Illuminate\Support\Str;
use App\Models\GalleryPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk menangani tampilan dan pengelolaan profil pengguna.
 * Menyediakan fungsi untuk melihat profil, memperbarui data profil,
 * riwayat transaksi, dan verifikasi tempat admin.
 */
class ProfileController extends Controller
{
    /**
     * Menampilkan halaman profil pengguna.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('user.profile.index');
    }

    /**
     * Memperbarui data profil pengguna.
     *
     * Mengupdate nama, email, nomor telepon, dan gambar profil
     * sesuai dengan data yang diterima dari request pengguna.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg|max:4096',
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

        return redirect()->route('profile')->with('success', __('flasher.profil_diperbarui'));
    }

    /**
     * Menampilkan riwayat transaksi pengguna.
     *
     * Mengambil semua transaksi yang dilakukan oleh pengguna yang sedang login,
     * dan menampilkannya dengan paginasi.
     *
     * @return \Illuminate\View\View
     */
    public function transactionHistory()
    {
        $transactions = Transaction::with('tickets')
            ->where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return view('user.profile.riwayat', compact('transactions'));
    }

    /**
     * Menampilkan halaman verifikasi tempat admin.
     *
     * Menampilkan pilihan destinasi yang tersedia dan status verifikasi tempat
     * yang diajukan oleh pengguna yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function adminPlaceVerification()
    {
        $destinations = Destination::select('id', 'name')->get();
        $adminPlace = AdminPlace::with(['place:id,name'])
            ->where('user_id', auth()->id())
            ->latest()
            ->first();

        return view('user.profile.adminverification', compact('destinations', 'adminPlace'));
    }

    /**
     * Menyimpan pengajuan verifikasi tempat baru.
     *
     * Menyimpan data tempat yang diajukan, termasuk dokumen kepemilikan, gambar galeri,
     * dan data lainnya yang dibutuhkan untuk proses verifikasi oleh admin.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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
            'gallery_images.*' => 'image|mimes:jpg,png,jpeg|max:5000', // Validasi gambar
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

        // Simpan gambar galeri jika ada
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $imagePath = $image->store('gallery/place', 'public'); // Simpan ke storage/public/gallery/place

                GalleryPlace::create([
                    'place_id' => $place->id,
                    'image_url' => "storage/$imagePath",
                    'image_type' => 'place', // Default sebagai gambar tempat
                ]);
            }
        }

        return redirect()->back()->with('success', __('flasher.pengajuan_verifikasi_dikirim'));
    }
}
