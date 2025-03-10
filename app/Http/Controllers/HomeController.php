<?php

namespace App\Http\Controllers;

use App\Models\Place;
use App\Models\Destination;
use Illuminate\Http\Request;
use App\Models\Recommendation;
use Illuminate\Support\Facades\DB;
use App\Models\RecommendationImage;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (Home).
     */
    public function index()
    {
        $promos = [
            'destinations' => Destination::with('promos')
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),
            'places' => Place::with('promos')
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),
        ];

        $categories = [
            'alams' => Destination::withAvg('reviews', 'rating')
                ->where('type', 'alam')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_destinations')
                        ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
                })
                ->limit(4)
                ->get(),

            'wahanas' => Destination::withAvg('reviews', 'rating')
                ->where('type', 'wahana')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_destinations')
                        ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
                })
                ->limit(4)
                ->get(),

            'places' => Place::withAvg('reviews', 'rating')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_places')
                        ->whereColumn('tbl_admin_places.place_id', 'tbl_places.id')
                        ->where('tbl_admin_places.approval_status', 'approved'); // Pastikan hanya tempat yang approved
                })
                ->limit(4)
                ->get(),
        ];

        $topRatedDestinations = Destination::withAvg('reviews', 'rating')
            ->orderBy('reviews_avg_rating', 'desc')
            ->limit(8)
            ->get();

        return view('user.home.index', compact('categories', 'promos', 'topRatedDestinations'));
    }

    public function submitRecommendation(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'description' => 'required|string',
            'image_url.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $location = json_encode([
            'address' => $request->address,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        $recommendation = Recommendation::create([
            'user_id' => auth()->user()->id,
            'destination_name' => $request->name,
            'description' => $request->description,
            'location' => $location,
            'status' => 'pending',
        ]);

        if ($request->hasFile('image_url')) {
            foreach ($request->file('image_url') as $image) {
                $filename = $image->hashName();
                $image->storeAs('public/recommendationimages', $filename);

                RecommendationImage::create([
                    'recommendation_id' => $recommendation->id,
                    'image_url' => 'storage/recommendationimages/' . $filename,
                ]);
            }
        }

        return redirect()->back()->with('success', 'Rekomendasi berhasil dikirim!');
    }

    public function indexAdmin()
    {
        return view('admin.dashboard.index3');
    }
}
