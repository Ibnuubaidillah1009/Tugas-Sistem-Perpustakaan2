@extends('layouts.dashboard')

@section('title', 'Dashboard Siswa')
@section('page-title', 'Dashboard Siswa')
@section('page-description', 'Kelola peminjaman buku Anda')

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-br from-green-600 via-teal-600 to-cyan-700 rounded-xl shadow-xl overflow-hidden">
        <div class="px-8 py-10 sm:px-10">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-6">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->photo)
                            <img src="{{ auth()->user()->photo_url }}" alt="User Photo" class="w-20 h-20 rounded-full object-cover border-4 border-white shadow-xl">
                        @else
                            <div class="w-20 h-20 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border-4 border-white/30">
                                <i class="fas fa-user text-3xl text-white"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2">
                            Selamat datang, {{ auth()->user()->name }}!
                        </h1>
                        <p class="text-green-100 text-lg">
                            Kelola peminjaman buku Anda dengan mudah dan efisien.
                        </p>
                        <div class="mt-3 flex items-center space-x-4 text-sm text-green-200">
                            <span class="flex items-center">
                                <i class="fas fa-graduation-cap mr-2"></i>
                                Siswa
                            </span>
                            <span class="flex items-center">
                                <i class="fas fa-id-card mr-2"></i>
                                NIS: {{ auth()->user()->nis ?? 'Belum diisi' }}
                            </span>
                        </div>
                    </div>
                </div>
                <div class="hidden md:block">
                    <div class="text-right">
                        <div class="text-green-200 text-sm mb-1">Dashboard Siswa</div>
                        <div class="text-white text-2xl font-bold">{{ now()->format('d M Y') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Buku -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Total Buku</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['total_books'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-book text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-chart-line text-green-500 mr-2"></i>
                        <span class="text-gray-600">Koleksi lengkap</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Buku Tersedia -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Buku Tersedia</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['available_books'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-check-circle text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check text-green-500 mr-2"></i>
                        <span class="text-gray-600">Siap dipinjam</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Peminjaman Saya -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Peminjaman Saya</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['my_borrowings'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-exchange-alt text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-history text-purple-500 mr-2"></i>
                        <span class="text-gray-600">Total riwayat</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sedang Dipinjam -->
        <div class="bg-white overflow-hidden shadow-lg rounded-xl hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-1 border border-gray-100">
            <div class="p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 mb-1">Sedang Dipinjam</p>
                        <p class="text-3xl font-bold text-gray-900">{{ $stats['active_borrowings'] }}</p>
                    </div>
                    <div class="w-14 h-14 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-lg">
                        <i class="fas fa-clock text-xl text-white"></i>
                    </div>
                </div>
                <div class="mt-4">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-clock text-yellow-500 mr-2"></i>
                        <span class="text-gray-600">Aktif saat ini</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Borrowings -->
        <div class="bg-white shadow-lg rounded-xl overflow-hidden border border-gray-100">
            <div class="px-6 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-green-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-exchange-alt text-white"></i>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">Peminjaman Saya</h3>
                            <p class="text-sm text-gray-500">Riwayat peminjaman buku</p>
                        </div>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        {{ $my_borrowings->count() }} item
                    </span>
                </div>
                <div class="space-y-4">
                    @forelse($my_borrowings as $borrowing)
                        <div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0">
                                <img src="{{ $borrowing->book->photo_url }}" alt="{{ $borrowing->book->title }} cover" class="w-14 h-20 object-cover rounded-lg shadow-sm">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-semibold text-gray-900 truncate mb-1">
                                            {{ $borrowing->book->title }}
                                        </p>
                                        <p class="text-sm text-gray-600 mb-2">
                                            <i class="fas fa-user mr-1"></i>{{ $borrowing->book->author }}
                                        </p>
                                        <div class="flex items-center space-x-4 text-xs text-gray-500">
                                            <span class="flex items-center">
                                                <i class="fas fa-calendar-plus mr-1"></i>
                                                {{ $borrowing->borrow_date->format('d M Y') }}
                                            </span>
                                            @if($borrowing->status === 'dipinjam')
                                                <span class="flex items-center">
                                                    <i class="fas fa-calendar-times mr-1"></i>
                                                    {{ $borrowing->due_date->format('d M Y') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex-shrink-0">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                            @if($borrowing->status === 'dipinjam') bg-green-100 text-green-800
                                            @elseif($borrowing->status === 'dikembalikan') bg-gray-100 text-gray-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $borrowing->status_text }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-book-open text-gray-400 text-xl"></i>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada peminjaman</p>
                            <p class="text-gray-400 text-xs mt-1">Mulai pinjam buku untuk melihat riwayat di sini</p>
                        </div>
                    @endforelse
                </div>
                @if($my_borrowings->count() > 0)
                    <div class="mt-6 pt-4 border-t border-gray-200">
                        <a href="{{ route('siswa.borrowings.index') }}" class="inline-flex items-center text-sm font-medium text-green-600 hover:text-green-500 transition-colors">
                            <span>Lihat semua peminjaman</span>
                            <i class="fas fa-arrow-right ml-2"></i>
                        </a>
                    </div>
                @endif
            </div>
        </div>

        <!-- Recent Books -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="px-6 py-5 sm:p-6">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg leading-6 font-semibold text-gray-900">
                        <i class="fas fa-book mr-2 text-blue-600"></i>
                        Buku Terbaru
                    </h3>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $recent_books->count() }} item
                    </span>
                </div>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
@forelse($recent_books as $book)
    <li class="py-4">
        <div class="flex items-center space-x-4">
            <div class="flex-shrink-0">
                <img src="{{ $book->photo_url }}" alt="{{ $book->title }} cover" class="w-12 h-16 object-cover rounded">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    {{ $book->title }}
                </p>
                <p class="text-sm text-gray-500">
                    {{ $book->author }}
                </p>
                <p class="text-xs text-gray-400">
                    Stok: {{ $book->stock }} | {{ $book->category ?? 'Tidak ada kategori' }}
                </p>
            </div>
            <div>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($book->stock > 0) bg-green-100 text-green-800
                    @else bg-red-100 text-red-800 @endif">
                    {{ $book->stock > 0 ? 'Tersedia' : 'Habis' }}
                </span>
            </div>
        </div>
    </li>
@empty
                            <li class="py-4 text-center text-gray-500">
                                Belum ada buku
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-4">
                    <a href="{{ route('siswa.books.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                        Lihat semua buku →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="px-6 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-semibold text-gray-900 mb-6">
                <i class="fas fa-bolt mr-2 text-yellow-600"></i>
                Aksi Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('siswa.books.index') }}"
                   class="group flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl hover:border-primary-300 hover:bg-primary-50 transition-all duration-200">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-primary-200 transition-colors">
                        <i class="fas fa-search text-xl text-primary-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Cari Buku</h4>
                    <p class="text-xs text-gray-500 text-center">Jelajahi koleksi buku</p>
                </a>

                <a href="{{ route('siswa.borrowings.index') }}"
                   class="group flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl hover:border-green-300 hover:bg-green-50 transition-all duration-200">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-green-200 transition-colors">
                        <i class="fas fa-list text-xl text-green-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Lihat Peminjaman</h4>
                    <p class="text-xs text-gray-500 text-center">Kelola peminjaman Anda</p>
                </a>

                <a href="{{ route('siswa.borrowings.create') }}"
                   class="group flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all duration-200">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-blue-200 transition-colors">
                        <i class="fas fa-plus text-xl text-blue-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Pinjam Buku</h4>
                    <p class="text-xs text-gray-500 text-center">Pinjam buku baru</p>
                </a>

                <a href="{{ route('profile.edit') }}"
                   class="group flex flex-col items-center p-6 border-2 border-gray-200 rounded-xl hover:border-purple-300 hover:bg-purple-50 transition-all duration-200">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center mb-3 group-hover:bg-purple-200 transition-colors">
                        <i class="fas fa-user text-xl text-purple-600"></i>
                    </div>
                    <h4 class="font-semibold text-gray-900 mb-1">Profil</h4>
                    <p class="text-xs text-gray-500 text-center">Edit profil Anda</p>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection