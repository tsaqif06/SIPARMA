<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Models\User;
use App\Models\Place;
use App\Models\Review;
use App\Models\Article;
use App\Models\Balance;
use App\Models\Facility;
use App\Models\AdminPlace;
use App\Models\BalanceLog;
use App\Models\Destination;
use App\Models\Transaction;
use App\Models\AdminBalance;
use App\Models\GalleryPlace;
use Illuminate\Http\Request;
use App\Models\Recommendation;
use App\Models\AdminBalanceLog;
use Illuminate\Support\Facades\DB;
use App\Models\RecommendationImage;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman utama (Home).
     */
    public function index()
    {
        // Promos
        $promos = [
            'destinations' => Destination::with(['promos', 'gallery'])
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),

            'places' => Place::with(['promos', 'gallery'])
                ->withAvg('reviews', 'rating')
                ->whereHas('promos', function ($query) {
                    $query->where('discount', '>', 0)
                        ->whereDate('valid_from', '<=', now())
                        ->whereDate('valid_until', '>=', now());
                })
                ->limit(4)
                ->get(),
        ];

        // Categories
        $categories = [
            'alams' => Destination::with(['gallery'])
                ->withAvg('reviews', 'rating')
                ->where('type', 'alam')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_destinations')
                        ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
                })
                ->limit(4)
                ->get(),

            'wahanas' => Destination::with(['gallery'])
                ->withAvg('reviews', 'rating')
                ->where('type', 'wahana')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_destinations')
                        ->whereColumn('tbl_admin_destinations.destination_id', 'tbl_destinations.id');
                })
                ->limit(4)
                ->get(),

            'places' => Place::with(['gallery', 'facilities'])
                ->withAvg('reviews', 'rating')
                ->whereExists(function ($query) {
                    $query->select(DB::raw(1))
                        ->from('tbl_admin_places')
                        ->whereColumn('tbl_admin_places.place_id', 'tbl_places.id')
                        ->where('tbl_admin_places.approval_status', 'approved');
                })
                ->limit(3)
                ->get(),
        ];

        // Top Rated Destinations
        $topRatedDestinations = Destination::with(['gallery'])
            ->withAvg('reviews', 'rating')
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

        return redirect()->back()->with('success', __('flasher.rekomendasi_dikirim'));
    }

    public function indexAdmin()
    {
        $user = auth()->user();
        $role = $user->role;

        // Data yang akan digunakan untuk semua role
        $data = [
            'total_users' => null,
            'total_balance' => null,
            'total_profit' => null,
            'total_rides' => null,
            'average_rating' => null,
            'total_destinations' => null,
            'total_places' => null,
            'total_transactions' => null,
            'recentTransactions' => collect(),
            'revenueData' => collect(),
            'total_facility' => null,
            'total_gallery' => null,
            'total_article' => null
        ];

        if ($role === 'superadmin') {
            // Query untuk superadmin
            $data['total_users'] = User::count();
            $data['total_balance'] = AdminBalance::sum('balance');
            $data['total_profit'] = AdminBalanceLog::sum('profit');
            $data['total_destinations'] = Destination::count();
            $data['total_places'] = Place::count();
            $data['total_transactions'] = Transaction::count();

            $data['recentTransactions'] = Transaction::with(['user:id,name'])
                ->select('id', 'user_id', 'transaction_code', 'amount', 'status', 'created_at')
                ->latest()
                ->limit(5)
                ->get();

            $data['revenueData'] = AdminBalanceLog::select(
                DB::raw('SUM(profit) as profit, period_year, period_month')
            )
                ->groupBy('period_year', 'period_month')
                ->orderBy('period_year')
                ->orderBy('period_month')
                ->get();
        } elseif ($role === 'admin_wisata') {
            // Query untuk admin wisata
            $destinationId = $user->adminDestinations[0]->destination_id;

            $balance = Balance::where('destination_id', $destinationId)->first();
            $data['total_profit'] = $balance->total_profit ?? 0;
            $data['total_balance'] = $balance->balance ?? 0;
            $data['total_rides'] = Ride::where('destination_id', $destinationId)->count();
            $data['average_rating'] = Review::where('destination_id', $destinationId)->avg('rating');
            $data['total_transactions'] = Transaction::where('destination_id', $destinationId)->count();

            $data['recentTransactions'] = Transaction::with(['user:id,name'])
                ->where('destination_id', $destinationId)
                ->select('id', 'user_id', 'transaction_code', 'amount', 'status', 'created_at')
                ->latest()
                ->limit(5)
                ->get();

            $data['revenueData'] = BalanceLog::where('destination_id', $destinationId)
                ->select(
                    DB::raw('SUM(profit) as profit, period_year, period_month')
                )
                ->groupBy('period_year', 'period_month')
                ->orderBy('period_year')
                ->orderBy('period_month')
                ->get();
        } elseif ($role === 'admin_tempat') {
            // Query untuk admin tempat
            $place = $user->adminPlaces[0]
                ->where('approval_status', 'approved')
                ->latest()
                ->first();

            if ($place) {
                $placeId = $place->place_id;
                $data['total_facility'] = Facility::where('item_type', 'place')
                    ->where('item_id', $placeId)
                    ->count();
                $data['total_gallery'] = GalleryPlace::where('place_id', $placeId)->count();
                $data['total_article'] = Article::where('user_id', $user->id)->count();
                $data['average_rating'] = Review::where('place_id', $placeId)->avg('rating');
            }

            return view('admin.dashboard.admintempat', $data);
        }

        // Konversi Revenue Data ke Chart Format
        $revenueLabels = [];
        $revenueSeries = [];
        foreach ($data['revenueData'] as $dataItem) {
            $revenueLabels[] = date('M', mktime(0, 0, 0, $dataItem->period_month, 10));
            $revenueSeries[] = $dataItem->profit;
        }

        // Tambahkan data ke array utama
        $data['revenueLabels'] = $revenueLabels;
        $data['revenueSeries'] = $revenueSeries;

        return view('admin.dashboard.index', $data);
    }
}
