<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Ride;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Destination;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminRideController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin_wisata') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }
    /**
     * Menampilkan daftar wahana berdasarkan destinasi.
     */
    public function index()
    {
        $rides = auth()->user()->adminDestinations[0]->destination->rides;

        foreach ($rides as $ride) {
            $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

            if ($ride->operational_status === 'closed') {
                $ride->operational_status = 'Tutup';
            } else {
                $ride->operational_status = ($current_time >= $ride->open_time && $current_time <= $ride->close_time)
                    ? 'Buka'
                    : 'Tutup';
            }
        }

        return view('admin.rides.index', compact('rides'));
    }

    /**
     * Menampilkan form untuk membuat wahana baru.
     */
    public function create()
    {
        return view('admin.rides.create');
    }

    /**
     * Menyimpan data wahana baru.
     */
    public function store(Request $request)
    {
        $request->validate([
            'destination_id' => 'required|integer|exists:tbl_destinations,id',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'price' => 'required|numeric',
            'weekend_price' => 'required|numeric',
            'children_price' => 'required|numeric',
            'min_age' => 'nullable|integer',
            'min_height' => 'nullable|integer',
        ]);

        Ride::create(array_merge(
            $request->all(),
            ['slug' => Str::slug($request->name)]
        ));

        return redirect()->route('admin.rides.index')
            ->with('success', 'Wahana berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ride $ride)
    {
        $current_time = Carbon::now('Asia/Jakarta')->format('H:i:s');

        if ($ride->operational_status === 'closed') {
            $ride->operational_status = 'Tutup';
        } else {
            $ride->operational_status = ($current_time >= $ride->open_time && $current_time <= $ride->close_time)
                ? 'Buka'
                : 'Tutup';
        }

        return view('admin.rides.show', compact('ride'));
    }

    /**
     * Menampilkan form untuk mengedit wahana.
     */
    public function edit(Ride $ride)
    {
        return view('admin.rides.edit', compact('ride'));
    }

    /**
     * Memperbarui data wahana.
     */
    public function update(Request $request, Ride $ride)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'open_time' => 'required',
            'close_time' => 'required',
            'operational_status' => 'required|in:open,closed',
            'price' => 'required|numeric',
            'weekend_price' => 'required|numeric',
            'children_price' => 'required|numeric',
            'min_age' => 'nullable|integer',
            'min_height' => 'nullable|integer',
        ]);

        $ride->update(array_merge(
            $request->all(),
            ['slug' => Str::slug($request->name)]
        ));

        $this->updateBundlesContainingItem($ride->id, 'ride');

        return redirect()->route('admin.rides.index')
            ->with('success', 'Wahana berhasil diperbarui.');
    }

    /**
     * Menghapus wahana dari database.
     */
    public function destroy(Ride $ride)
    {
        $ride->delete();

        return redirect()->route('admin.rides.index')
            ->with('success', 'Wahana berhasil dihapus.');
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
