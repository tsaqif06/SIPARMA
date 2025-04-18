<?php

namespace App\Http\Controllers;

use App\Models\AdminPlace;
use App\Models\GalleryRide;
use App\Models\GalleryPlace;
use Illuminate\Http\Request;
use App\Models\GalleryDestination;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola galeri gambar (destination, place, ride).
 */
class AdminGalleryController extends Controller
{
    /**
     * Menampilkan daftar gambar berdasarkan jenis galeri.
     *
     * @param string $type Jenis galeri ('destination', 'place', 'ride')
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($type)
    {
        $galleryModel = $this->getGalleryModel($type);

        if (!$galleryModel) {
            return redirect()->back()->with('error', 'Jenis galeri tidak valid.');
        }

        $allImages = $galleryModel->gallery()->get();
        $placeImages = $galleryModel->gallery()->where('image_type', 'place')->get();
        $promoImages = $galleryModel->gallery()->where('image_type', 'promo')->get();

        $menuImages = null;
        if ($type === 'place') {
            $menuImages = $galleryModel->gallery()->where('image_type', 'menu')->get();
        }

        return view('admin.gallery.index', compact('type', 'allImages', 'placeImages', 'promoImages', 'menuImages'));
    }

    /**
     * Menampilkan form untuk menambahkan gambar baru ke galeri.
     *
     * @param string $type Jenis galeri
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($type)
    {
        $galleryModel = $this->getGalleryModel($type);

        if (!$galleryModel) {
            return redirect()->back()->with('error', 'Jenis galeri tidak valid.');
        }

        return view('admin.gallery.create', compact('type'));
    }

    /**
     * Menyimpan gambar ke galeri berdasarkan jenis.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $type)
    {
        $request->validate([
            'image_url' => 'required|image|mimes:jpeg,png,jpg,gif|max:4096',
            'image_type' => 'nullable|in:place,promo,menu',
        ]);

        $galleryModel = $this->getModel($type);
        if (!$galleryModel) {
            return redirect()->back()->with('error', 'Jenis galeri tidak valid.');
        }

        if ($request->hasFile('image_url')) {
            $file = $request->file('image_url');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/gallery/' . $type, $fileName);
            $picturePath = "storage/gallery/{$type}/{$fileName}";
        }

        $foreignKey = $this->getForeignKey($type);
        $relatedId = $this->getRelatedId($type);

        $galleryModel::create([
            $foreignKey => $relatedId,
            'image_url' => $picturePath,
            'image_type' => $request->image_type ?? null,
        ]);

        return redirect()->route('admin.gallery.index', ['type' => $type])
            ->with('success', 'Gambar telah ditambahkan.');
    }

    /**
     * Menghapus gambar dari galeri.
     *
     * @param string $type
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($type, $id)
    {
        $galleryModel = $this->getModel($type);
        if (!$galleryModel) {
            return redirect()->back()->with('error', 'Jenis galeri tidak valid.');
        }

        $image = $galleryModel::findOrFail($id);

        // Hapus file fisik dari storage
        $imagePath = public_path($image->image_url);
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }

        // Hapus data dari database
        $image->delete();

        return redirect()->route('admin.gallery.index', ['type' => $type])
            ->with('success', 'Gambar telah dihapus.');
    }

    /**
     * Mendapatkan nama model berdasarkan jenis galeri.
     *
     * @param string $type
     * @return string|null
     */
    private function getModel($type)
    {
        return match ($type) {
            'destination' => GalleryDestination::class,
            'place' => GalleryPlace::class,
            'ride' => GalleryRide::class,
            default => null,
        };
    }

    /**
     * Mengambil instance model galeri berdasarkan user dan jenis.
     *
     * @param string $type
     * @return mixed|null
     */
    private function getGalleryModel($type)
    {
        $user = auth()->user();

        return match ($type) {
            'destination' => $user->adminDestinations[0]->destination ?? null,
            'place' => AdminPlace::where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->latest('created_at')
                ->first()?->place,
            'ride' => GalleryRide::class,
            default => null,
        };
    }

    /**
     * Mengambil ID yang terkait dengan galeri berdasarkan jenis.
     *
     * @param string $type
     * @return int|null
     */
    private function getRelatedId($type)
    {
        $user = auth()->user();

        return match ($type) {
            'destination' => $user->adminDestinations[0]->destination_id ?? null,
            'place' => AdminPlace::where('user_id', $user->id)
                ->where('approval_status', 'approved')
                ->latest('created_at')
                ->first()?->place_id,
            'ride' => $user->adminRides[0]->ride_id ?? null,
            default => null,
        };
    }

    /**
     * Mendapatkan nama foreign key berdasarkan jenis galeri.
     *
     * @param string $type
     * @return string|null
     */
    private function getForeignKey($type)
    {
        return match ($type) {
            'destination' => 'destination_id',
            'place' => 'place_id',
            'ride' => 'ride_id',
            default => null,
        };
    }
}
