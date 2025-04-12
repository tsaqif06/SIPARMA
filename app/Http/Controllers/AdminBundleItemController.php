<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\Bundle;
use App\Models\BundleItem;
use App\Models\Destination;
use Illuminate\Http\Request;

/**
 * Controller untuk mengelola item dalam sebuah bundle wisata.
 * Hanya dapat diakses oleh admin_wisata.
 */
class AdminBundleItemController extends Controller
{
    /**
     * Menampilkan daftar item dalam sebuah bundle.
     *
     * @param Bundle $bundle
     * @return \Illuminate\View\View
     */
    public function index(Bundle $bundle)
    {
        $items = $bundle->items;
        return view('admin.bundles.items.index', compact('bundle', 'items'));
    }

    /**
     * Mengambil data rides berdasarkan destination milik admin yang sedang login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getRides()
    {
        $destinationId = auth()->user()->adminDestinations[0]->destination_id;

        $rides = Ride::where('destination_id', $destinationId)
            ->select('id', 'name')
            ->get();

        return response()->json($rides);
    }

    /**
     * Menampilkan form untuk menambahkan item ke dalam bundle.
     *
     * @param Bundle $bundle
     * @return \Illuminate\View\View
     */
    public function create(Bundle $bundle)
    {
        return view('admin.bundles.items.create', compact('bundle'));
    }

    /**
     * Menyimpan item baru ke dalam bundle.
     *
     * @param Request $request
     * @param Bundle $bundle
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menampilkan form edit item dalam bundle.
     *
     * @param Bundle $bundle
     * @param BundleItem $item
     * @return \Illuminate\View\View
     */
    public function edit(Bundle $bundle, BundleItem $item)
    {
        return view('admin.bundles.items.edit', compact('bundle', 'item'));
    }

    /**
     * Memperbarui item dalam bundle.
     *
     * @param Request $request
     * @param Bundle $bundle
     * @param BundleItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Menghapus item dari bundle.
     *
     * @param Bundle $bundle
     * @param BundleItem $item
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Bundle $bundle, BundleItem $item)
    {
        $item->delete();

        $this->updateBundleTotalPrice($bundle->id);

        return redirect()->route('admin.bundle.items.index', $bundle->id)
            ->with('success', 'Item berhasil dihapus dari bundle.');
    }

    /**
     * Menghitung ulang total harga bundle berdasarkan item-itemnya.
     *
     * @param int $bundleId
     * @return void
     */
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
