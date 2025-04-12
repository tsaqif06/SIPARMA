<?php

namespace App\Http\Controllers;

use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * Controller untuk menangani tampilan dan pengelolaan data tempat.
 * Menyediakan fungsi untuk menampilkan detail tempat dan melakukan pencarian tempat.
 */
class PlaceController extends Controller
{
    /**
     * Menampilkan halaman detail tempat berdasarkan slug.
     *
     * Mengambil data tempat beserta galeri, fasilitas, dan ulasan-ulasan
     * yang terkait dengan tempat tersebut. Ulasan juga akan dipaginasi
     * untuk membatasi jumlah ulasan yang ditampilkan.
     *
     * @param  string  $slug
     * @return \Illuminate\View\View
     */
    public function show($slug)
    {
        $place = Place::with([
            'gallery',
            'facilities',
            'reviews' => function ($query) {
                // Mengambil ulasan terbaru dan terkait dengan pengguna yang memberikan ulasan
                $query->with(['user:id,name,profile_picture'])
                    ->latest()
                    ->paginate(5);
            }
        ])->where('slug', $slug)->firstOrFail();

        // Mendapatkan ulasan untuk tempat dengan pagination
        $reviews = $place->reviews()->paginate(5);

        // Mengembalikan tampilan halaman detail tempat
        return view('user.places.show', compact('place', 'reviews'));
    }

    /**
     * Menampilkan daftar tempat yang dapat dicari dan difilter.
     *
     * Mengambil daftar tempat berdasarkan filter yang diberikan oleh pengguna
     * seperti jenis tempat, pencarian berdasarkan nama, dan pengurutan berdasarkan rating atau waktu pembuatan.
     * Hanya tempat yang sudah disetujui oleh admin yang akan ditampilkan.
     *
     * @param  \Illuminate\Http\Request
     * @return \Illuminate\View\View
     */
    public function browse(Request $request)
    {
        $query = Place::with([
            'gallery',
            'facilities',
            'reviews'
        ])
            ->withAvg('reviews', 'rating') // Menambahkan rata-rata rating ulasan untuk setiap tempat
            ->whereExists(function ($query) {
                $query->select(DB::raw(1))
                    ->from('tbl_admin_places')
                    ->where('tbl_admin_places.approval_status', 'approved') // Memastikan hanya tempat yang disetujui yang ditampilkan
                    ->whereColumn('tbl_admin_places.place_id', 'tbl_places.id');
            });

        // Filter berdasarkan jenis tempat (misalnya restoran, penginapan, dll)
        if ($request->has('jenis_tempat')) {
            $jenisTempat = $request->jenis_tempat;

            if (in_array('Other', $jenisTempat)) {
                $query->whereNotIn('type', ['restoran', 'penginapan']);
            } else {
                $query->whereIn('type', $jenisTempat);
            }
        }

        // Pencarian berdasarkan nama tempat
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Pengurutan berdasarkan parameter yang diberikan (populer atau rating tertinggi)
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'populer':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                case 'rating_tertinggi':
                    $query->orderBy('reviews_avg_rating', 'desc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        }

        // Mengambil data tempat dengan pagination
        $places = $query->paginate(9);

        // Mengembalikan tampilan daftar tempat yang difilter
        return view('user.places.browse', compact('places'));
    }
}
