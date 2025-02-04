<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Destination;
use Illuminate\Http\Request;

class AdminBundleItemController extends Controller
{
    public function index(Bundle $bundle)
    {
        $items = $bundle->items;
        return view('admin.bundles.items.index', compact('bundle', 'items'));
    }

    public function getRides()
    {
        $destinationId = auth()->user()->adminDestinations[0]->destination_id;

        $rides = Ride::where('destination_id', $destinationId)
            ->select('id', 'name')
            ->get();

        return response()->json($rides);
    }

    // public function getItems(Request $request)
    // {
    //     $type = $request->query('type');

    //     if ($type == 'destination') {
    //         return response()->json(Destination::select('id', 'name')->get());
    //     } elseif ($type == 'ride') {
    //         return response()->json(Ride::select('id', 'name')->get());
    //     }

    //     return response()->json([]);
    // }

    public function create(Bundle $bundle)
    {
        return view('admin.bundles.items.create', compact('bundle'));
    }

    public function store(Request $request, Bundle $bundle)
    {
        $request->validate([
            'item_type' => 'required|in:destination,ride',
            'item_id' => 'required|integer',
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
        ]);

        BundleItem::create([
            'bundle_id' => $bundle->id,
            'item_type' => $request->item_type,
            'item_id' => $request->item_id,
            'quantity' => json_encode([
                'adults' => $request->adults,
                'children' => $request->children,
            ]),
        ]);

        $this->updateBundleTotalPrice($bundle->id);

        return redirect()->route('admin.bundle.items.index', $bundle->id)
            ->with('success', 'Item berhasil ditambahkan ke bundle.');
    }

    public function edit(Bundle $bundle, BundleItem $item)
    {
        return view('admin.bundles.items.edit', compact('bundle', 'item'));
    }

    public function update(Request $request, Bundle $bundle, BundleItem $item)
    {
        $request->validate([
            'item_type' => 'required|in:destination,ride',
            'item_id' => 'required|integer',
            'adults' => 'required|integer|min:0',
            'children' => 'required|integer|min:0',
        ]);

        $item->update([
            'item_type' => $request->item_type,
            'item_id' => $request->item_id,
            'quantity' => json_encode([
                'adults' => $request->adults,
                'children' => $request->children,
            ]),
        ]);

        $this->updateBundleTotalPrice($bundle->id);

        return redirect()->route('admin.bundle.items.index', $bundle->id)
            ->with('success', 'Item dalam bundle berhasil diperbarui.');
    }

    public function destroy(Bundle $bundle, BundleItem $item)
    {
        $item->delete();

        $this->updateBundleTotalPrice($bundle->id);

        return redirect()->route('admin.bundle.items.index', $bundle->id)
            ->with('success', 'Item berhasil dihapus dari bundle.');
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
