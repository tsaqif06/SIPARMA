<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

/**
 * Controller untuk menangani ulasan (review) pengguna.
 * Menyediakan fungsi untuk menyimpan ulasan, menghapus ulasan,
 * serta memfilter kata-kata buruk dalam komentar ulasan.
 */
class ReviewController extends Controller
{
    /**
     * Memfilter kata-kata buruk dalam komentar ulasan.
     *
     * Fungsi ini menerima teks dan menggantikan kata-kata yang termasuk dalam
     * daftar kata buruk dengan tanda '***'.
     *
     * @param  string  $text
     * @return string
     */
    public function filterBadWords($text)
    {
        $badWords = [
            'anjing',
            'babi',
            'bangsat',
            'brengsek',
            'goblok',
            'tolol',
            'idiot',
            'kampret',
            'kontol',
            'memek',
            'ngentot',
            'pepek',
            'peler',
            'pler',
            'setan',
            'sialan',
            'tai',
            'jembut',
            'kenthu',
            'keparat',
            'asu',
            'bencong',
            'banci',
            'lonte',
            'pelacur',
            'sundal',
            'perek',
            'bego',
            'dongo',
            'dungu',
            'gembel',
            'laknat',
            'mampus',
            'mati lu',
            'kuburan lu',
            'kimak',
            'fuckboy',
            'fuckgirl',
            'fuck',
            'shit',
            'asshole',
            'bitch',
            'bastard',
            'dick',
            'cunt',
            'motherfucker',
            'pussy',
            'slut',
            'whore',
            'dumbass',
            'retard',
            'moron',
            'stupid',
            'jerk',
            'prick',
            'wanker',
            'bollocks',
            'twat',
            'bloody hell',
            'dipshit',
            'faggot',
            'cock',
            'scumbag',
            'nigger',
            'nigga',
            'hoe',
            'jackass',
            'douchebag'
        ];

        return str_ireplace($badWords, '***', $text);
    }

    /**
     * Menyimpan ulasan baru.
     *
     * Fungsi ini menerima request dari pengguna, memvalidasi data, kemudian menyimpan
     * ulasan yang sudah difilter (kata-kata buruk diganti dengan '***').
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',  // Validasi rating antara 1 dan 5
            'comment' => 'required|string',               // Validasi komentar (harus berupa string)
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',  // Validasi ID destinasi
            'place_id' => 'nullable|integer|exists:tbl_places,id',             // Validasi ID tempat
        ]);

        // Memfilter komentar untuk kata-kata buruk
        $cleanComment = $this->filterBadWords($request->comment);

        // Menyimpan ulasan ke database
        Review::create([
            'user_id' => auth()->user()->id,
            'destination_id' => $request->destination_id,
            'place_id' => $request->place_id,
            'rating' => $request->rating,
            'comment' => $cleanComment,
        ]);

        return redirect()->back()->with('success', __('flasher.ulasan_dikirim'));
    }

    /**
     * Menghapus ulasan berdasarkan ID.
     *
     * Fungsi ini akan menghapus ulasan yang ditemukan berdasarkan ID yang diberikan.
     * Jika ulasan tidak ditemukan, akan diberikan pesan kesalahan.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $review = Review::find($id); // Mencari ulasan berdasarkan ID
        if ($review) {
            $review->delete();  // Menghapus ulasan
            return redirect()->back()->with('success', __('flasher.ulasan_dihapus'));
        } else {
            return redirect()->back()->with('error', __('flasher.ulasan_tidak_ditemukan'));
        }
    }
}
