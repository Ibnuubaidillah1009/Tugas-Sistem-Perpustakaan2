@extends('layouts.dashboard')

@section('title', 'Detail User')
@section('page-title', 'Detail User')
@section('page-description', 'Informasi lengkap tentang user')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <div class="h-12 w-12 rounded-full bg-primary-100 flex items-center justify-center">
                        <i class="fas fa-user text-primary-600 text-xl"></i>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $user->name }}</h1>
                        <p class="text-primary-100">{{ $user->email }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($user->role === 'guru') bg-blue-100 text-blue-800
                        @else bg-green-100 text-green-800 @endif">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ ucfirst($user->role) }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- User Details -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi User</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
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
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($user->role) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Bergabung</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d M Y H:i') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Terakhir Diperbarui</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Borrowing History -->
                        @if($user->borrowings->count() > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Peminjaman</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flow-root">
                                        <ul class="-my-5 divide-y divide-gray-200">
                                            @foreach($user->borrowings->take(10) as $borrowing)
                                                <li class="py-3">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-book text-gray-400"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $borrowing->book->title }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $borrowing->book->author }}
                                                            </p>
                                                            <p class="text-sm text-gray-500">
                                                                {{ $borrowing->borrow_date->format('d M Y') }} - 
                                                                @if($borrowing->return_date)
                                                                    {{ $borrowing->return_date->format('d M Y') }}
                                                                @else
                                                                    Belum dikembalikan
                                                                @endif
                                                            </p>
                                                        </div>
                                                        <div>
                                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                                @if($borrowing->status === 'borrowed') bg-green-100 text-green-800
                                                                @elseif($borrowing->status === 'returned') bg-gray-100 text-gray-800
                                                                @else bg-red-100 text-red-800 @endif">
                                                                {{ ucfirst($borrowing->status) }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                    @if($user->borrowings->count() > 10)
                                        <p class="mt-3 text-sm text-gray-500">
                                            Dan {{ $user->borrowings->count() - 10 }} peminjaman lainnya...
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @else
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Peminjaman</h3>
                                <div class="bg-gray-50 rounded-lg p-8 text-center">
                                    <i class="fas fa-book text-4xl text-gray-400 mb-4"></i>
                                    <p class="text-gray-500">User ini belum pernah meminjam buku.</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Actions Sidebar -->
                <div class="lg:col-span-1">
                    <div class="bg-gray-50 rounded-lg p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                        
                        <div class="space-y-3">
                            <a href="{{ route('perpustakawan.users.edit', $user) }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-edit mr-2"></i>
                                Edit User
                            </a>
                            
                            <form method="POST" action="{{ route('perpustakawan.users.destroy', $user) }}" 
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                        class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                    <i class="fas fa-trash mr-2"></i>
                                    Hapus User
                                </button>
                            </form>
                            
                            <a href="{{ route('perpustakawan.users.index') }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>

                        <!-- User Stats -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Total Peminjaman</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $user->borrowings->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Sedang Dipinjam</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $user->borrowings->where('status', 'borrowed')->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Sudah Dikembalikan</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $user->borrowings->where('status', 'returned')->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Terlambat</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $user->borrowings->where('status', 'overdue')->count() }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection