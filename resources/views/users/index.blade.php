@extends('layouts.dashboard')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-description', 'Kelola akun guru dan siswa')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">Daftar User</h3>
            <p class="text-sm text-gray-500">Kelola akun guru dan siswa dalam sistem</p>
        </div>
        
        <a href="{{ route('perpustakawan.users.create') }}" 
           class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <i class="fas fa-plus mr-2"></i>
            Tambah User
        </a>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan nama atau email..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="flex gap-2">
                <select name="role" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Role</option>
                    <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                    <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <i class="fas fa-search"></i>
                </button>
                @if(request()->hasAny(['search', 'role']))
                    <a href="{{ route('perpustakawan.users.index') }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Users Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($users as $user)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                @if($user->photo)
                                    <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"
                                         class="h-10 w-10 rounded-full object-cover border-2 border-gray-300">
                                @else
                                    <div class="h-10 w-10 rounded-full bg-primary-100 flex items-center justify-center">
                                        <i class="fas fa-user text-primary-600"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $user->name }}
                                    </h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($user->role === 'guru') bg-blue-100 text-blue-800
                                        @else bg-green-100 text-green-800 @endif">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-envelope mr-1"></i>
                                    {{ $user->email }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Bergabung: {{ $user->created_at->format('d M Y') }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('perpustakawan.users.show', $user) }}" 
                               class="text-blue-600 hover:text-blue-800">
                                <i class="fas fa-eye"></i>
                            </a>
                            
                            <a href="{{ route('perpustakawan.users.edit', $user) }}" 
                               class="text-yellow-600 hover:text-yellow-800">
                                <i class="fas fa-edit"></i>
                            </a>
                            
                            <form method="POST" action="{{ route('perpustakawan.users.destroy', $user) }}" 
                                  class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-12 text-center">
                    <i class="fas fa-users text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada user</h3>
                    <p class="text-gray-500">Belum ada guru atau siswa yang terdaftar.</p>
                    <a href="{{ route('perpustakawan.users.create') }}" 
                       class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                        <i class="fas fa-plus mr-2"></i>
                        Tambah User Pertama
                    </a>
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="mt-6">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection