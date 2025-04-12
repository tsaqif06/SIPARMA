<?php

namespace App\Http\Controllers;

use App\Models\Balance;
use App\Models\BalanceLog;
use App\Models\AdminBalance;
use App\Models\AdminBalanceLog;
use App\Models\AdminDestination;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk manajemen saldo dan log saldo di panel admin.
 */
class AdminBalanceController extends Controller
{
    /**
     * Konstruktor controller. Menetapkan middleware autentikasi
     * dan membatasi akses untuk role tertentu.
     */
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
     * Menampilkan daftar saldo berdasarkan peran pengguna:
     * - Superadmin: semua saldo.
     * - Admin wisata: hanya saldo destinasi yang dikelolanya.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->role === 'superadmin') {
            $balances = Balance::with('destination')->get();
        } else if (Auth::user()->role === 'admin_wisata') {
            $destinationId = auth()->user()->adminDestinations[0]->destination_id;
            $balances = Balance::with('destination')->where('destination_id', $destinationId)->get();
        }

        return view('admin.balance.index', compact('balances'));
    }

    /**
     * Menampilkan daftar saldo pusat (AdminBalance).
     * Hanya untuk superadmin.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function indexAdmin()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
        }

        $balances = AdminBalance::all();

        return view('admin.balance.indexAdmin', compact('balances'));
    }

    /**
     * Menampilkan detail saldo berdasarkan ID.
     * - Superadmin: bisa akses semua.
     * - Admin wisata: hanya bisa melihat destinasi yang dikelolanya.
     *
     * @param int $id
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $balance = Balance::with('destination')->where('id', $id)->firstOrFail();

        if (auth()->user()->role === 'superadmin') {
            return view('admin.balance.show', compact('balance'));
        } else {
            $adminDestinations = AdminDestination::where('user_id', auth()->user()->id)
                ->pluck('destination_id')
                ->toArray();

            if (in_array($balance->destination_id, $adminDestinations)) {
                return view('admin.balance.show', compact('balance'));
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
        }
    }

    /**
     * Menampilkan rekap saldo bulanan untuk admin wisata.
     * Superadmin tidak diperbolehkan mengakses halaman ini.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function monthlyRecapIndex()
    {
        if (Auth::user()->role === 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        $destinationId = auth()->user()->adminDestinations[0]->destination_id;

        $balanceLogs = BalanceLog::where('destination_id', $destinationId)
            ->orderBy('period_year', 'desc')
            ->orderBy('period_month', 'desc')
            ->get();

        return view('admin.balance.recap', compact('balanceLogs'));
    }

    /**
     * Menampilkan rekap saldo bulanan pusat (AdminBalanceLog).
     * Hanya untuk superadmin.
     *
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function monthlyRecapIndexAdmin()
    {
        if (Auth::user()->role !== 'superadmin') {
            return redirect()->route('admin.dashboard');
        }

        $balanceLogs = AdminBalanceLog::all();

        return view('admin.balance.recapAdmin', compact('balanceLogs'));
    }
}
