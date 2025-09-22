@extends('layouts.dashboard')

@section('title', 'Dashboard Guru')
@section('page-title', 'Dashboard Guru')
@section('page-description', 'Kelola peminjaman buku Anda')

@section('content')
<div class="space-y-6">
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        @if(auth()->user()->photo)
                            <img src="{{ auth()->user()->photo_url }}" alt="User Photo" class="w-12 h-12 rounded-full object-cover">
                        @else
                            <i class="fas fa-user text-2xl text-primary-600"></i>
                        @endif
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Total Buku</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['total_books'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-2xl text-green-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Buku Tersedia</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['available_books'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exchange-alt text-2xl text-blue-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Peminjaman Saya</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['my_borrowings'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-2xl text-yellow-600"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sedang Dipinjam</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $stats['active_borrowings'] }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- My Borrowings -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    Peminjaman Saya
                </h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($my_borrowings as $borrowing)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-book text-primary-600"></i>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-medium text-gray-900 truncate">
                                            {{ $borrowing->book->title }}
                                        </p>
                                        <p class="text-sm text-gray-500">
                                            {{ $borrowing->book->author }}
                                        </p>
                                        <p class="text-xs text-gray-400">
                                            Dipinjam: {{ $borrowing->borrow_date->format('d M Y') }}
                                            @if($borrowing->status === 'borrowed')
                                                | Jatuh tempo: {{ $borrowing->due_date->format('d M Y') }}
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
                        @empty
                            <li class="py-4 text-center text-gray-500">
                                Belum ada peminjaman
                            </li>
                        @endforelse
                    </ul>
                </div>
                <div class="mt-4">
                    <a href="{{ route('guru.borrowings.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                        Lihat semua peminjaman →
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Books -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                    <i class="fas fa-book mr-2"></i>
                    Buku Terbaru
                </h3>
                <div class="flow-root">
                    <ul class="-my-5 divide-y divide-gray-200">
                        @forelse($recent_books as $book)
                            <li class="py-4">
                                <div class="flex items-center space-x-4">
                                    <div class="flex-shrink-0">
                                        <i class="fas fa-book text-primary-600"></i>
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
                    <a href="{{ route('guru.books.index') }}" class="text-sm text-primary-600 hover:text-primary-500">
                        Lihat semua buku →
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                <i class="fas fa-bolt mr-2"></i>
                Aksi Cepat
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('guru.books.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-search text-2xl text-primary-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Cari Buku</h4>
                        <p class="text-sm text-gray-500">Jelajahi koleksi buku yang tersedia</p>
                    </div>
                </a>
                
                <a href="{{ route('guru.borrowings.index') }}" 
                   class="flex items-center p-4 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    <i class="fas fa-list text-2xl text-green-600 mr-3"></i>
                    <div>
                        <h4 class="font-medium text-gray-900">Lihat Peminjaman</h4>
                        <p class="text-sm text-gray-500">Kelola peminjaman Anda</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection