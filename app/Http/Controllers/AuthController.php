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
        return view('user.auth.login');
    }

    public function showLoginFormAdmin()
    {
        return view('admin.auth.login');
    }

    public function showRegisterForm()
    {
        return view('user.auth.register');
    }

    public function showRegisterFormAdmin()
    {
        return view('admin.auth.register');
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
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
            $remember = $request->has('remember');

            if (Auth::attempt(['email' => $validated['email'], 'password' => $validated['password']], $remember)) {
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

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:tbl_users,email',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'nullable|regex:/^[0-9]{10,15}$/',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone_number' => $validated['phone_number'],
        ]);

        return redirect('/login');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/');
    }
}
