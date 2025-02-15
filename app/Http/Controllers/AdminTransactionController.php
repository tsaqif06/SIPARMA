<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\AdminDestination;
use Illuminate\Support\Facades\Auth;


class AdminTransactionController extends Controller
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
            $transactions = Transaction::all();
        }
        else if (Auth::user()->role === 'admin_wisata') {
            $destinationId = auth()->user()->adminDestinations[0]->destination_id;
            $transactions = Transaction::where('destination_id', $destinationId)->get();
        }

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Display the specified resource.
     */
    public function show($code)
    {
        $transaction = Transaction::where('transaction_code', $code)->firstOrFail();

        if (auth()->user()->role === 'superadmin') {
            return view('admin.transactions.show', compact('transaction'));
        } else {
            $adminDestinations = AdminDestination::where('user_id', auth()->user()->id)->pluck('destination_id')->toArray();

            if (in_array($transaction->destination_id, $adminDestinations)) {
                return view('admin.transactions.show', compact('transaction'));
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
        }
    }
}
