<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    // public function store(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:tbl_users',
    //         'password' => 'required|min:6',
    //     ]);

    //     $validatedData['password'] = bcrypt($validatedData['password']);

    //     User::create($validatedData);

    //     return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan');
    // }

    // public function update(Request $request, $id)
    // {
    //     $user = User::findOrFail($id);

    //     $validatedData = $request->validate([
    //         'name' => 'required|string|max:255',
    //         'email' => 'required|email|unique:tbl_users,email,' . $id,
    //     ]);

    //     $user->update($validatedData);

    //     return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui');
    // }

    // public function delete($id)
    // {
    //     $user = User::findOrFail($id);
    //     $user->delete();

    //     return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus');
    // }
}
