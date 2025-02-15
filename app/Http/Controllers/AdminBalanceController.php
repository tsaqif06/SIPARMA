<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\AdminDestination;
use Illuminate\Support\Facades\Auth;


class AdminBalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

        $this->middleware(function ($request, $next) {
            if (auth()->user()->role === 'admin_tempat') {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
            return $next($request);
        });
    }
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

        if (Auth::user()->role === 'superadmin') {
            $balances = Balance::all();
        }
        else if (Auth::user()->role === 'admin_wisata') {
            $destinationId = auth()->user()->adminDestinations[0]->destination_id;
            $balances = Balance::where('destination_id', $destinationId)->get();
        }

        return view('admin.balance.index', compact('balances'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $balance = Balance::where('id', $id)->firstOrFail();

        if (auth()->user()->role === 'superadmin') {
            return view('admin.balance.show', compact('balance'));
        } else {
            $adminDestinations = AdminDestination::where('user_id', auth()->user()->id)->pluck('destination_id')->toArray();

            if (in_array($balance->destination_id, $adminDestinations)) {
                return view('admin.balance.show', compact('balance'));
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
        }
    }
}
