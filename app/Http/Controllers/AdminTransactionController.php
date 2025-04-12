<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\AdminDestination;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk mengelola transaksi oleh Superadmin dan Admin Wisata.
 */
class AdminTransactionController extends Controller
{
    /**
     * Konstruktor: Mengatur middleware autentikasi dan role akses.
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
     * Menampilkan daftar transaksi berdasarkan peran pengguna.
     * - Superadmin: Semua transaksi
     * - Admin Wisata: Transaksi berdasarkan destinasi yang dikelola
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        if (Auth::user()->role === 'superadmin') {
            $transactions = Transaction::latest()->get(); // Ambil semua transaksi
        } else if (Auth::user()->role === 'admin_wisata') {
            $destinationId = auth()->user()->adminDestinations[0]->destination_id;
            $transactions = Transaction::where('destination_id', $destinationId)
                ->latest()
                ->get();
        }

        return view('admin.transactions.index', compact('transactions'));
    }

    /**
     * Menampilkan detail transaksi berdasarkan kode transaksi.
     * Akses dibatasi berdasarkan peran dan kepemilikan destinasi.
     *
     * @param string $code Kode transaksi
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($code)
    {
        $transaction = Transaction::where('transaction_code', $code)->firstOrFail();

        if (auth()->user()->role === 'superadmin') {
            return view('admin.transactions.show', compact('transaction'));
        } else {
            $adminDestinations = AdminDestination::where('user_id', auth()->user()->id)
                ->pluck('destination_id')
                ->toArray();

            if (in_array($transaction->destination_id, $adminDestinations)) {
                return view('admin.transactions.show', compact('transaction'));
            } else {
                return redirect()->route('admin.dashboard')->with('error', 'Akses ditolak!');
            }
        }
    }
}
