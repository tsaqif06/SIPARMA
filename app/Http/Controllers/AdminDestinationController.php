<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ride;
use App\Models\Promo;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Recommendation;
use Illuminate\Support\Facades\Auth;


class AdminDestinationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (Auth::user()->role !== 'superadmin') {
            if (Auth::user()->role === 'admin_wisata') {
                return redirect()->route('admin.destinations.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
            }
            return redirect()->route('admin.places.show', auth()->user()->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
        }

        $destinations = Destination::all();

        foreach ($destinations as $destination) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($destination->operational_status === 'holiday') {
                $destination->status = 'Libur';
            } else {
                $destination->status = ($current_time >= $destination->open_time && $current_time <= $destination->close_time)
                    ? 'Buka'
                    : 'Tutup';
            }
        }

        return view('admin.destinations.index', compact('destinations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (Auth::user()->role !== 'superadmin') {
            if (Auth::user()->role === 'admin_wisata') {
                return redirect()->route('admin.destinations.show', auth()->user()->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
            }
            return redirect()->route('admin.places.show', auth()->user()->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
        }

        return view('admin.destinations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:alam,wahana',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $location = [
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ];

        Destination::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'type' => $validated['type'],
            'location' => json_encode($location),
        ]);

        return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah ditambahkan.');
    }

    public function recommendation()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $recommendations = Recommendation::with('user')->orderBy('created_at', 'desc')->get();

        return view('admin.destinations.recommendation', compact('recommendations'));
    }

    // Menampilkan detail rekomendasi beserta gambarnya
    public function showRecommendation($id)
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $recommendation = Recommendation::with(['user', 'images'])->findOrFail($id);
        return view('admin.destinations.recommendationshow', compact('recommendation'));
    }

    public function changeStatus($id)
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $recommendation = Recommendation::with(['user', 'images'])->findOrFail($id);
        return view('admin.destinations.changestatus', compact('recommendation'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected',
        ]);

        $recommendation = Recommendation::findOrFail($id);
        $recommendation->status = $request->status;
        $recommendation->save();

        // Redirect kembali ke halaman daftar rekomendasi dengan pesan sukses
        return redirect()->route('admin.destinations.recommendation')->with('success', 'Status rekomendasi berhasil diubah!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Destination $destination)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminDestinations->contains('destination_id', $destination->id)) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($destination->operational_status === 'holiday') {
                $destination->status = 'Libur';
            } else {
                $destination->status = ($current_time >= $destination->open_time && $current_time <= $destination->close_time)
                    ? 'Buka'
                    : 'Tutup';
            }

            $today = now()->toDateString();
            $promo = Promo::where('destination_id', $destination->id)
                ->whereDate('valid_from', '<=', $today)
                ->whereDate('valid_until', '>=', $today)
                ->first();

            return view('admin.destinations.show', compact('destination', 'promo'));
        }

        if (Auth::user()->role === 'admin_wisata') {
            return redirect()->route('admin.destinations.show', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }
        return redirect()->route('admin.places.show', $user->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Destination $destination)
    {
        $user = auth()->user();

        if ($user->role === 'superadmin' || $user->adminDestinations->contains('destination_id', $destination->id)) {
            return view('admin.destinations.edit', compact('destination'));
        }

        if (Auth::user()->role === 'admin_wisata') {
            return redirect()->route('admin.destinations.edit', $user->adminDestinations[0]->destination_id)->with('error', 'Akses ditolak!');
        }
        return redirect()->route('admin.places.edit', $user->adminPlaces[0]->place_id)->with('error', 'Akses ditolak!');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Destination $destination)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'type' => 'required|string|in:alam,wahana',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'open_time' => 'nullable',
            'close_time' => 'nullable',
            'price' => 'nullable|numeric|min:0',
            'weekend_price' => 'nullable|numeric|min:0',
            'children_price' => 'nullable|numeric|min:0',
            'account_number' => 'nullable|string|max:50',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
            'operational_status' => 'nullable|in:open,holiday',
            'description' => 'nullable|string|max:1000',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $validated['location'] = json_encode([
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
        ]);

        $destination->update($validated);

        $this->updateBundlesContainingItem($destination->id, 'destination');

        if (auth()->user()->role === 'superadmin') {
            return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah diupdate.');
        } else {
            return redirect()->route('admin.destinations.show', $destination->id)
                ->with('success', 'Wisata telah diupdate.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Destination $destination)
    {
        foreach ($destination->gallery as $image) {
            $imagePath = public_path($image->image_url);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $destination->delete();

        return redirect()->route('admin.destinations.index')->with('success', 'Wisata telah dihapus.');
    }

    private function updateBundlesContainingItem($itemId, $itemType)
    {
        $bundleItems = BundleItem::where('item_id', $itemId)
            ->where('item_type', $itemType)
            ->get();

        foreach ($bundleItems as $bundleItem) {
            $this->updateBundleTotalPrice($bundleItem->bundle_id);
        }
    }

    private function updateBundleTotalPrice($bundleId)
    {
        $bundleItems = BundleItem::where('bundle_id', $bundleId)->get();

        $totalPrice = 0;

        foreach ($bundleItems as $bundleItem) {
            $quantity = json_decode($bundleItem->quantity, true);
            if ($bundleItem->item_type === 'destination') {
                $item = Destination::find($bundleItem->item_id);
            } else {
                $item = Ride::find($bundleItem->item_id);
            }

            if ($item) {
                $totalPrice += ($item->price * $quantity['adults']) + ($item->children_price * $quantity['children']);
            }
        }

        Bundle::where('id', $bundleId)->update(['total_price' => $totalPrice]);
    }
}
