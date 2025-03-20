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
                ->limit(3)
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
        $user = auth()->user();
        $role = $user->role;

        $total_users = null;
        $total_balance = null;
        $total_profit = null;
        $total_rides = null;
        $average_rating = null;
        $total_destinations = null;
        $total_places = null;
        $total_transactions = null;
        $recentTransactions = collect();
        $revenueData = collect();

        if ($role === 'superadmin') {
            $total_users = DB::table('tbl_users')->count();
            $total_balance = DB::table('tbl_admin_balance')->sum('balance');
            $total_profit = DB::table('tbl_admin_balance_logs')->sum('profit');
            $total_destinations = DB::table('tbl_destinations')->count();
            $total_places = DB::table('tbl_places')->count();
            $total_transactions = DB::table('tbl_transactions')->count();

            $recentTransactions = DB::table('tbl_transactions')
                ->join('tbl_users', 'tbl_transactions.user_id', '=', 'tbl_users.id')
                ->select(
                    'tbl_transactions.id',
                    'tbl_users.name as user_name',
                    'tbl_transactions.transaction_code',
                    'tbl_transactions.amount',
                    'tbl_transactions.status',
                    'tbl_transactions.created_at'
                )
                ->latest('tbl_transactions.created_at')
                ->limit(5)
                ->get();

            // **Revenue untuk Superadmin dari tbl_admin_balance_logs**
            $revenueData = DB::table('tbl_admin_balance_logs')
                ->select(DB::raw('SUM(profit) as profit, period_year, period_month'))
                ->groupBy('period_year', 'period_month')
                ->orderBy('period_year', 'asc')
                ->orderBy('period_month', 'asc')
                ->get();
        } elseif ($role === 'admin_wisata') {
            $total_profit = DB::table('tbl_balance')
                ->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
                ->value('total_profit');

            $total_balance = DB::table('tbl_balance')->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
                ->value('balance');;


            $total_rides = DB::table('tbl_rides')
                ->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
                ->count();

            $average_rating = DB::table('tbl_reviews')
                ->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
                ->avg('rating');

            $total_transactions = DB::table('tbl_transactions')->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)->count();

            $recentTransactions = DB::table('tbl_transactions')
                ->join('tbl_users', 'tbl_transactions.user_id', '=', 'tbl_users.id')
                ->where('destination_id', auth()->user()->adminDestinations[0]->destination_id)
                ->select(
                    'tbl_transactions.id',
                    'tbl_users.name as user_name',
                    'tbl_transactions.transaction_code',
                    'tbl_transactions.amount',
                    'tbl_transactions.status',
                    'tbl_transactions.created_at'
                )
                ->latest('tbl_transactions.created_at')
                ->limit(5)
                ->get();

            // **Revenue untuk Admin Wisata dari tbl_balance_logs**
            $revenueData = DB::table('tbl_balance_logs')
                ->select(DB::raw('SUM(profit) as profit, period_year, period_month'))
                ->groupBy('period_year', 'period_month')
                ->orderBy('period_year', 'asc')
                ->orderBy('period_month', 'asc')
                ->get();
        } elseif ($role === 'admin_tempat') {
            $total_facility = DB::table('tbl_facilities')
                ->where([
                    ['item_type', 'place'],
                    ['item_id', auth()->user()->adminPlaces[0]->place_id],
                ])
                ->count();

            $total_gallery = DB::table('tbl_gallery_places')
                ->where('place_id', auth()->user()->adminPlaces[0]->place_id)
                ->count();

            $total_article = DB::table('tbl_articles')
                ->where('user_id', auth()->user()->id)
                ->count();

            $average_rating = DB::table('tbl_reviews')
                ->where('place_id', auth()->user()->adminPlaces[0]->place_id)
                ->avg('rating');

            return view('admin.dashboard.admintempat', compact(
                'total_facility',
                'total_gallery',
                'total_article',
                'average_rating',
            ));
        }

        // **Konversi Revenue Data ke Chart Format**
        $revenueLabels = [];
        $revenueSeries = [];
        foreach ($revenueData as $data) {
            $revenueLabels[] = date('M', mktime(0, 0, 0, $data->period_month, 10)); // Format bulan (Jan, Feb, dst)
            $revenueSeries[] = $data->profit;
        }

        // Kirim data ke view
        return view('admin.dashboard.index', compact(
            'total_users',
            'total_destinations',
            'total_places',
            'total_transactions',
            'total_balance',
            'total_profit',
            'total_rides',
            'average_rating',
            'recentTransactions',
            'revenueLabels',
            'revenueSeries'
        ));
    }
}
