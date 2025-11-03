<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'perpustakawan');

        // Apply search filter
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%')
                  ->orWhere('nip', 'like', '%' . $search . '%')
                  ->orWhere('nis', 'like', '%' . $search . '%');
            });
        }

        // Apply role filter
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $users = $query->latest()->paginate(10)->appends(request()->query());

        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

public function store(Request $request)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'role' => 'required|in:guru,siswa',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    if ($request->role === 'guru') {
        $rules['nip'] = 'required|string|max:255';
        $rules['nis'] = 'nullable|string|max:255';
    } elseif ($request->role === 'siswa') {
        $rules['nis'] = 'required|string|max:255';
        $rules['nip'] = 'nullable|string|max:255';
    }

    $request->validate($rules);

    $photoName = null;
    if ($request->hasFile('photo')) {
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('images'), $photoName);
    }

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'role' => $request->role,
        'nip' => $request->nip,
        'nis' => $request->nis,
        'photo' => $photoName,
    ]);

    return redirect()->route('perpustakawan.users.index')
        ->with('success', 'User berhasil ditambahkan.');
}

public function update(Request $request, User $user)
{
    $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        'role' => 'required|in:guru,siswa',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ];

    if ($request->role === 'guru') {
        $rules['nip'] = 'required|string|max:255';
        $rules['nis'] = 'nullable|string|max:255';
    } elseif ($request->role === 'siswa') {
        $rules['nis'] = 'required|string|max:255';
        $rules['nip'] = 'nullable|string|max:255';
    }

    $request->validate($rules);

    $photoName = $user->photo;
    if ($request->hasFile('photo')) {
        // Delete old photo if exists
        if ($user->photo && file_exists(public_path('images/' . $user->photo))) {
            unlink(public_path('images/' . $user->photo));
        }

        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('images'), $photoName);
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'nip' => $request->nip,
        'nis' => $request->nis,
        'photo' => $photoName,
    ]);

    if ($request->password) {
        $user->update(['password' => Hash::make($request->password)]);
    }

    return redirect()->route('perpustakawan.users.index')
        ->with('success', 'User berhasil diperbarui.');
}

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function destroy(User $user)
    {
        // Delete photo if exists
        if ($user->photo && file_exists(public_path('images/' . $user->photo))) {
            unlink(public_path('images/' . $user->photo));
        }

        $user->delete();

        return redirect()->route('perpustakawan.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    public function editProfile()
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'nip' => 'nullable|string|max:255',
            'nis' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoName = $user->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->photo && file_exists(public_path('images/' . $user->photo))) {
                unlink(public_path('images/' . $user->photo));
            }

            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images'), $photoName);
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'nip' => $request->nip,
            'nis' => $request->nis,
            'photo' => $photoName,
        ]);

        if ($request->password) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->back()
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
