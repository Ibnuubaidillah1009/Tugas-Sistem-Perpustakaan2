@extends('layouts.dashboard')

@section('title', 'Dashboard Perpustakawan')
@section('page-title', 'Dashboard Perpustakawan')
@section('page-description', 'Kelola sistem perpustakaan dengan mudah')

@section('content')
<div class="space-y-6 bg-[#f8fafc] min-h-screen p-4 rounded-lg">
    <!-- Welcome Header -->
    <div class="bg-white rounded-xl shadow-md border border-gray-100">
        <div class="px-6 py-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800 mb-2">
                        Selamat Datang, {{ auth()->user()->name }}!
                    </h1>
                    <div class="flex items-center space-x-4 text-gray-600 mb-4">
                        <span class="flex items-center">
                            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i>
                            {{ now()->format('l, d F Y') }}
                        </span>
                        <span class="flex items-center">
                            <i class="fas fa-user-tie mr-2 text-blue-600"></i>
                            Perpustakawan
                        </span>
                    </div>
                    <p class="text-gray-700">
                        Kelola perpustakaan Anda dengan mudah dan efisien.
                    </p>
                </div>

                <div class="mt-6 lg:mt-0">
                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                        <div class="text-center">
                            <p class="text-gray-500 text-sm mb-2">Waktu Saat Ini</p>
                            <p class="text-xl font-mono font-semibold text-blue-700" id="current-time">{{ now()->format('H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['title' => 'Total Buku', 'value' => number_format($stats['total_books']), 'icon' => 'fa-book'],
                ['title' => 'Dipinjam', 'value' => number_format($stats['active_borrowings']), 'icon' => 'fa-clock'],
                ['title' => 'Terlambat', 'value' => number_format($stats['overdue_borrowings']), 'icon' => 'fa-exclamation-triangle'],
                ['title' => 'Total Denda', 'value' => 'Rp '.number_format($stats['total_fines'], 0, ',', '.'), 'icon' => 'fa-money-bill-wave'],
            ];
        @endphp

        @foreach($cards as $card)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 hover:shadow-md transition duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-3xl font-bold text-gray-800 mb-1">{{ $card['value'] }}</div>
                    <div class="text-sm font-medium text-gray-600">{{ $card['title'] }}</div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas {{ $card['icon'] }} text-xl text-blue-600"></i>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Recent Borrowings -->
        <div class="lg:col-span-2 bg-white rounded-xl shadow-md border border-gray-100">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-semibold text-gray-800">
                    <i class="fas fa-exchange-alt mr-2 text-blue-600"></i>Peminjaman Terbaru
                </h3>
                <a href="{{ route('perpustakawan.borrowings.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                    Lihat Semua →
                </a>
            </div>

            <div class="p-6">
                @forelse($recent_borrowings as $borrowing)
                    <div class="flex items-center space-x-4 p-4 rounded-lg hover:bg-gray-50 transition">
                        <img src="{{ $borrowing->book->photo_url }}" alt="{{ $borrowing->book->title }}" class="w-16 h-20 object-cover rounded-lg border">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">{{ $borrowing->book->title }}</h4>
                            <p class="text-sm text-gray-600"><i class="fas fa-user mr-1"></i>{{ $borrowing->user->name }}</p>
                            <p class="text-xs text-gray-500 mt-1"><i class="fas fa-calendar mr-1"></i>{{ $borrowing->borrow_date->format('d M Y') }}</p>
                        </div>
                        <span class="px-3 py-1 rounded-full text-xs font-semibold
                            @if($borrowing->status === 'dipinjam')
                                bg-green-100 text-green-700
                            @elseif($borrowing->status === 'dikembalikan')
                                bg-gray-100 text-gray-700
                            @else
                                bg-red-100 text-red-700
                            @endif">
                            {{ $borrowing->status_text }}
                        </span>
                    </div>
                    @if(!$loop->last)
                        <hr class="my-3 border-gray-100">
                    @endif
                @empty
                    <div class="text-center py-6 text-gray-500">
                        <i class="fas fa-inbox text-4xl mb-2 text-gray-300"></i>
                        <p>Belum ada peminjaman</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Right: Sidebar -->
        <div class="space-y-6">
            <!-- Recent Books -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                    <h3 class="text-lg font-semibold text-gray-800">
                        <i class="fas fa-book mr-2 text-blue-600"></i>Buku Terbaru
                    </h3>
                    <a href="{{ route('perpustakawan.books.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                        Lihat Semua →
                    </a>
                </div>

                <div class="p-6">
                    @forelse($recent_books as $book)
                        <div class="flex items-center space-x-4 p-3 rounded-lg hover:bg-gray-50 transition">
                            <img src="{{ $book->photo_url }}" class="w-12 h-16 object-cover rounded border">
                            <div class="flex-1">
                                <h4 class="font-semibold text-gray-800 text-sm">{{ $book->title }}</h4>
                                <p class="text-xs text-gray-600">{{ $book->author }}</p>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-6 text-gray-500">
                            <i class="fas fa-book text-3xl mb-2 text-gray-300"></i>
                            <p>Belum ada buku terbaru</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-xl shadow-md border border-gray-100 p-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">
                    <i class="fas fa-bolt mr-2 text-blue-600"></i>Aksi Cepat
                </h3>
                <div class="space-y-3">
                    @php
                        $actions = [
                            ['route'=>'books.create','icon'=>'fa-plus','title'=>'Tambah Buku','desc'=>'Tambahkan buku baru'],
                            ['route'=>'borrowings.create','icon'=>'fa-exchange-alt','title'=>'Pinjam Buku','desc'=>'Catat peminjaman buku'],
                            ['route'=>'users.create','icon'=>'fa-user-plus','title'=>'Tambah User','desc'=>'Daftarkan pengguna baru'],
                        ];
                    @endphp

                    @foreach($actions as $act)
                        <a href="{{ route('perpustakawan.'.$act['route']) }}" class="flex items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-xl border border-blue-100 transition">
                            <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center text-white mr-4">
                                <i class="fas {{ $act['icon'] }}"></i>
                            </div>
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $act['title'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $act['desc'] }}</p>
                            </div>
                            <i class="fas fa-chevron-right text-blue-400 ml-auto"></i>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function updateTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('id-ID', { hour12: false });
        document.getElementById('current-time').textContent = timeString;
    }
    setInterval(updateTime, 1000);
</script>
@endsection
