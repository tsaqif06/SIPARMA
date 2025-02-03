<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facility;
use Illuminate\Support\Facades\Storage;

class AdminFacilityController extends Controller
{
    /**
     * Menampilkan daftar gambar berdasarkan jenis galeri (destination, place, ride).
     */
    public function index($type)
    {
        $facilities = $this->getFacilityModel($type);

        if (!$facilities) {
            return redirect()->back()->with('error', 'Jenis fasilitas tidak valid.');
        }

        $nama = $type == 'destination' ? auth()->user()->adminDestinations[0]->destination->name : auth()->user()->adminPlaces[0]->place->name;

        return view('admin.facility.index', compact('type', 'facilities', 'nama'));
    }

    public function create($type)
    {
        $facilityModel = $this->getFacilityModel($type);

        if (!$facilityModel) {
            return redirect()->back()->with('error', 'Jenis fasilitas tidak valid.');
        }

        $destinationId = auth()->user()->adminDestinations[0]->destination_id;

        return view('admin.facility.create', compact('type', 'destinationId'));
    }

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

    // Menampilkan form untuk mengedit fasilitas
    public function edit($type, Facility $facility)
    {
        return view('admin.facility.edit', compact('facility', 'type'));
    }


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

    // Menghapus fasilitas dari database
    public function destroy($type, Facility $facility)
    {
        $facility->delete();

        return redirect()->route('admin.facility.index', $type)
            ->with('success', 'Fasilitas telah dihapus.');
    }

    /**
     * Fungsi helper untuk menentukan model berdasarkan jenis galeri.
     */
    private function getFacilityModel($type)
    {
        return match ($type) {
            'destination' => auth()->user()->adminDestinations[0]->destination->facilities,
            'place' => auth()->user()->adminPlace[0]->place->facilites,
            default => null,
        };
    }
}
