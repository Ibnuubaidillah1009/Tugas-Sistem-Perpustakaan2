<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('role', '!=', 'perpustakawan')
            ->latest()
            ->paginate(10);
        
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:guru,siswa',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $photoName = null;
    if ($request->hasFile('photo')) {
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/photos/users', $photoName);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'photo' => $photoName,
    ]);

    return redirect()->route('perpustakaan.users.index')
        ->with('success', 'User berhasil ditambahkan.');
}

public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|in:perpustakaan,guru,siswa',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $photoName = $user->photo;
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo) {
            Storage::delete('public/photos/users/' . $user->photo);
        }
        
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/photos/users', $photoName);
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'photo' => $photoName,
    ]);

    if ($request->password) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    return redirect()->route('perpustakaan.users.index')
        ->with('success', 'User berhasil diperbarui.');
    }
}