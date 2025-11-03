@extends('layouts.dashboard')

@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('page-description', 'Informasi lengkap user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center space-x-3">
                <i class="fas fa-user text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">Detail User</h1>
                    <p class="text-primary-100">{{ $user->name }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Profile Photo -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Foto Profil</h3>
                        @if($user->photo)
                            <div class="flex justify-center">
                                <img src="{{ $user->photo_url }}" alt="{{ $user->name }}" 
                                     class="w-48 h-48 object-cover rounded-lg border-4 border-white shadow-lg">
                            </div>
                        @else
                            <div class="flex justify-center">
                                <div class="w-48 h-48 bg-gray-300 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-user text-6xl text-gray-500"></i>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- User Information -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Nama Lengkap</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $user->role === 'guru' ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </dd>
                                </div>
                                @if($user->role === 'guru' && $user->nip)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIP</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->nip }}</dd>
                                </div>
                                @elseif($user->role === 'siswa' && $user->nis)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">NIS</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->nis }}</dd>
                                </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Bergabung Sejak</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Statistics -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary-600">{{ $user->borrowings()->count() }}</div>
                                    <div class="text-sm text-gray-500">Total Peminjaman</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $user->borrowings()->where('status', 'dikembalikan')->count() }}</div>
                                    <div class="text-sm text-gray-500">Buku Dikembalikan</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $user->borrowings()->where('status', 'dipinjam')->count() }}</div>
                                    <div class="text-sm text-gray-500">Buku Dipinjam</div>
                                </div>
                            </div>
                        </div>

                        <!-- Recent Borrowings -->
                        @if($user->borrowings()->count() > 0)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Peminjaman Terbaru</h3>
                            <div class="space-y-3">
                                @foreach($user->borrowings()->latest()->take(5)->get() as $borrowing)
                                <div class="flex items-center justify-between p-3 bg-white rounded-md border">
                                    <div class="flex items-center space-x-3">
                                        @if($borrowing->book->photo)
                                            <img src="{{ $borrowing->book->photo_url }}" alt="{{ $borrowing->book->title }}" 
                                                 class="w-10 h-10 object-cover rounded">
                                        @else
                                            <div class="w-10 h-10 bg-gray-300 rounded flex items-center justify-center">
                                                <i class="fas fa-book text-gray-500"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $borrowing->book->title }}</div>
                                            <div class="text-xs text-gray-500">{{ $borrowing->borrow_date->format('d M Y') }}</div>
                                        </div>
                                    </div>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $borrowing->status === 'dikembalikan' ? 'bg-green-100 text-green-800' :

                                           ($borrowing->status === 'dipinjam' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $borrowing->status_text }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                <a href="{{ route('perpustakawan.users.index') }}"
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <i class="fas fa-arrow-left mr-2"></i>
                    Kembali
                </a>
                <a href="{{ route('perpustakawan.users.edit', $user) }}"
                   class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <i class="fas fa-edit mr-2"></i>
                    Edit User
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
