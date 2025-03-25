<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
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
    public function store(Request $request)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string',
            'destination_id' => 'nullable|integer|exists:tbl_destinations,id',
            'place_id' => 'nullable|integer|exists:tbl_places,id',
        ]);

        $cleanComment = $this->filterBadWords($request->comment);

        Review::create([
            'user_id' => auth()->user()->id,
            'destination_id' => $request->destination_id,
            'place_id' => $request->place_id,
            'rating' => $request->rating,
            'comment' => $cleanComment,
        ]);

        return redirect()->back()->with('success', __('flasher.ulasan_dikirim'));
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if ($review) {
            $review->delete();
            return redirect()->back()->with('success', __('flasher.ulasan_dihapus'));
        } else {
            return redirect()->back()->with('error', __('flasher.ulasan_tidak_ditemukan'));
        }
    }
}
