<?php

namespace App\Http\Controllers;

use App\Models\Facility;
use App\Models\AdminPlace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Controller untuk mengelola fasilitas (facility) destinasi dan tempat.
 */
class AdminFacilityController extends Controller
{
    /**
     * Menampilkan daftar fasilitas berdasarkan jenis (destination/place).
     *
     * @param string $type Jenis item ('destination' atau 'place')
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index($type)
    {
        $facilities = $this->getFacilityModel($type);
        if (!$facilities) {
            return redirect()->back()->with('error', 'Jenis fasilitas tidak valid.');
        }

        $nama = $type == 'destination'
            ? auth()->user()->adminDestinations[0]->destination->name
            : AdminPlace::where('user_id', auth()->user()->id)
            ->where('approval_status', 'approved')
            ->latest('created_at')
            ->first()?->place->name;

        return view('admin.facility.index', compact('type', 'facilities', 'nama'));
    }

    /**
     * Menampilkan form untuk membuat fasilitas baru.
     *
     * @param string $type Jenis item ('destination' atau 'place')
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($type)
    {
        $facilityModel = $this->getFacilityModel($type);
        if (!$facilityModel) {
            return redirect()->back()->with('error', 'Jenis fasilitas tidak valid.');
        }

        $itemId = $this->getRelatedId($type);

        return view('admin.facility.create', compact('type', 'itemId'));
    }

    /**
     * Menyimpan data fasilitas baru ke database.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $type
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $type)
    {
        $request->validate([
            'item_type' => 'required|in:destination,place',
            'item_id' => 'required|integer',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        Facility::create($request->all());

        return redirect()->route('admin.facility.index', ['type' => $type])
            ->with('success', 'Fasilitas telah ditambahkan.');
    }

    /**
     * Menampilkan form edit fasilitas.
     *
     * @param string $type
     * @param \App\Models\Facility $facility
     * @return \Illuminate\View\View
     */
    public function edit($type, Facility $facility)
    {
        return view('admin.facility.edit', compact('facility', 'type'));
    }

    /**
     * Memperbarui data fasilitas yang ada.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $type
     * @param \App\Models\Facility $facility
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $type, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $facility->update([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.facility.index', ['type' => $type])
            ->with('success', 'Fasilitas berhasil diperbarui.');
    }

    /**
     * Menghapus fasilitas dari database.
     *
     * @param string $type
     * @param \App\Models\Facility $facility
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($type, Facility $facility)
    {
        $facility->delete();

        return redirect()->route('admin.facility.index', $type)
            ->with('success', 'Fasilitas telah dihapus.');
    }

    /**
     * Mengambil fasilitas berdasarkan tipe (destination/place).
     *
     * @param string $type
     * @return \Illuminate\Support\Collection|null
     */
    private function getFacilityModel($type)
    {
        return match ($type) {
            'destination' => auth()->user()->adminDestinations[0]->destination->facilities,
            'place' => AdminPlace::where('user_id', auth()->user()->id)
                ->where('approval_status', 'approved')
                ->latest('created_at')
                ->first()?->place->facilities,
            default => null,
        };
    }

    /**
     * Mengambil ID item terkait (destination_id atau place_id).
     *
     * @param string $type
     * @return int|null
     */
    private function getRelatedId($type)
    {
        return match ($type) {
            'destination' => auth()->user()->adminDestinations[0]->destination_id ?? null,
            'place' => AdminPlace::where('user_id', auth()->user()->id)
                ->where('approval_status', 'approved')
                ->latest('created_at')
                ->first()?->place_id,
            default => null,
        };
    }
}
