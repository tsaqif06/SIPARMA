<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('user.auth.login');
    }

    public function showLoginFormAdmin()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('admin.auth.login');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            if (in_array(Auth::user()->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
                return redirect('/admin');
            }
            return redirect('/');
        }

        return view('user.auth.register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // $remember = $request->has('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            return redirect()->route('home.index')->with('success', 'Login berhasil! Selamat datang');
        }

        return back()->with('error', 'Email atau password salah.');
    }

    public function loginAdmin(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $user = User::where('email', $validated['email'])->first();

        if ($user && in_array($user->role, ['superadmin', 'admin_wisata', 'admin_tempat'])) {
            if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('success', 'Login berhasil! Selamat datang, ' . $user->name);
            } else {
                return redirect()
                    ->route('admin.dashboard')
                    ->with('error', 'Email atau password salah.');
            }
        }

        return back()->with('error', $user ? 'Akun tidak memiliki akses admin.' : 'Email atau password salah.');
    }

    public function convertRoleAndLogin()
    {
        $user = User::find(Auth::id());

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User tidak ditemukan']);
        }

        if ($user->role !== 'admin_tempat') {
            $user->role = 'admin_tempat';
            $user->save();
        }

        return response()->json(['success' => true]);
    }



    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'required|regex:/^[0-9]{10,30}$/|max:30',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect('/login')->with('success', 'Daftar berhasil! Silahkan login dengan akun anda.');
    }

    public function logout()
    {
        $redirect = auth()->user()->role === 'user' ? '/' : '/admin';

        Auth::logout();
        return redirect($redirect);
    }
}
