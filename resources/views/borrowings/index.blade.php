@extends('layouts.dashboard')

@section('title', 'Daftar Peminjaman')
@section('page-title', 'Daftar Peminjaman')
@section('page-description', 'Kelola peminjaman buku')

@section('content')
<div class="space-y-6">
    <!-- Header Actions -->
    <div class="flex justify-between items-center">
        <div>
            <h3 class="text-lg font-medium text-gray-900">
                @if(auth()->user()->isPerpustakawan())
                    Semua Peminjaman
                @else
                    Peminjaman Saya
                @endif
            </h3>
            <p class="text-sm text-gray-500">
                @if(auth()->user()->isPerpustakawan())
                    Kelola semua peminjaman dalam sistem
                @else
                    Kelola peminjaman buku Anda
                @endif
            </p>
        </div>
        
        @if(auth()->user()->isPerpustakawan())
            <a href="{{ route('perpustakawan.borrowings.create') }}" 
               class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                <i class="fas fa-plus mr-2"></i>
                Tambah Peminjaman
            </a>
        @endif
    </div>

    <!-- Search and Filter -->
    <div class="bg-white p-4 rounded-lg shadow">
        <form method="GET" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari berdasarkan nama user atau judul buku..." 
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div class="flex gap-2">
                <select name="status" class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua Status</option>
                    <option value="borrowed" {{ request('status') == 'borrowed' ? 'selected' : '' }}>Sedang Dipinjam</option>
                    <option value="returned" {{ request('status') == 'returned' ? 'selected' : '' }}>Sudah Dikembalikan</option>
                    <option value="overdue" {{ request('status') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                </select>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-md hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500">
                    <i class="fas fa-search"></i>
                </button>
                @if(request()->hasAny(['search', 'status']))
                    <a href="{{ request()->url() }}" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Borrowings Table -->
    <div class="bg-white shadow overflow-hidden sm:rounded-md">
        <ul class="divide-y divide-gray-200">
            @forelse($borrowings as $borrowing)
                <li class="px-6 py-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="flex-shrink-0">
                                <img src="{{ $borrowing->book->photo_url }}" alt="{{ $borrowing->book->title }}" class="w-8 h-8 rounded object-cover">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-3">
                                    <h4 class="text-sm font-medium text-gray-900 truncate">
                                        {{ $borrowing->book->title }}
                                    </h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        @if($borrowing->status === 'borrowed') bg-green-100 text-green-800
                                        @elseif($borrowing->status === 'returned') bg-gray-100 text-gray-800
                                        @else bg-red-100 text-red-800 @endif">
                                        {{ $borrowing->status_text }}
                                    </span>
                                </div>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-user mr-1"></i>
                                    {{ $borrowing->user->name }} ({{ ucfirst($borrowing->user->role) }})
                                </p>
                                <p class="text-sm text-gray-500">
                                    <i class="fas fa-calendar mr-1"></i>
                                    Dipinjam: {{ $borrowing->borrow_date->format('d M Y') }} | 
                                    Jatuh tempo: {{ $borrowing->due_date->format('d M Y') }}
                                    @if($borrowing->return_date)
                                        | Dikembalikan: {{ $borrowing->return_date->format('d M Y') }}
                                    @endif
                                </p>
                                @if($borrowing->fine_amount > 0)
                                    <p class="text-sm text-red-600 font-medium">
                                        <i class="fas fa-exclamation-triangle mr-1"></i>
                                        Denda: Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}
                                        @if($borrowing->fine_paid)
                                            <span class="text-green-600">(Lunas)</span>
                                        @else
                                            <span class="text-red-600">(Belum Lunas)</span>
                                        @endif
                                    </p>
                                @endif
                                @if($borrowing->notes)
                                    <p class="text-sm text-gray-500 mt-1">
                                        <i class="fas fa-sticky-note mr-1"></i>
                                        {{ $borrowing->notes }}
                                    </p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex items-center space-x-2">
                            @if($borrowing->status === 'borrowed')
                                @if(auth()->user()->isPerpustakawan())
                                    <a href="{{ route('perpustakawan.borrowings.edit', $borrowing) }}"
                                       class="text-yellow-600 hover:text-yellow-800">
                                        <i class="fas fa-edit"></i>
                                    </a>

                                    <form method="POST" action="{{ route('perpustakawan.borrowings.return', $borrowing) }}"
                                          class="inline" onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-green-600 hover:text-green-800">
                                            <i class="fas fa-undo"></i>
                                        </button>
                                    </form>
                                @endif
                            @endif
                            
                            @if($borrowing->fine_amount > 0 && !$borrowing->fine_paid)
                                @php
                                    $payFineRoute = auth()->user()->isPerpustakawan() ? 
                                        route('perpustakawan.borrowings.pay-fine', $borrowing) : 
                                        (auth()->user()->isGuru() ? 
                                            route('guru.borrowings.pay-fine', $borrowing) : 
                                            route('siswa.borrowings.pay-fine', $borrowing));
                                @endphp
                                <form method="POST" action="{{ $payFineRoute }}" 
                                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin membayar denda Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="text-blue-600 hover:text-blue-800" title="Bayar Denda">
                                        <i class="fas fa-credit-card"></i>
                                    </button>
                                </form>
                            @endif
                            
                            @if(auth()->user()->isPerpustakawan())
                                <a href="{{ route('perpustakawan.borrowings.show', $borrowing) }}" 
                                   class="text-blue-600 hover:text-blue-800">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('perpustakawan.borrowings.print-receipt', $borrowing) }}"
                                   target="_blank"
                                   class="text-blue-600 hover:text-blue-800 ml-2" title="Cetak Struk">
                                    <i class="fas fa-print"></i>
                                </a>
                                
                                <form method="POST" action="{{ route('perpustakawan.borrowings.destroy', $borrowing) }}" 
                                      class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus peminjaman ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </li>
            @empty
                <li class="px-6 py-12 text-center">
                    <i class="fas fa-exchange-alt text-4xl text-gray-400 mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada peminjaman</h3>
                    <p class="text-gray-500">
                        @if(auth()->user()->isPerpustakawan())
                            Belum ada peminjaman dalam sistem.
                        @else
                            Anda belum meminjam buku apapun.
                        @endif
                    </p>
                    @if(auth()->user()->isPerpustakawan())
                        <a href="{{ route('perpustakawan.borrowings.create') }}" 
                           class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
                            <i class="fas fa-plus mr-2"></i>
                            Tambah Peminjaman Pertama
                        </a>
                    @endif
                </li>
            @endforelse
        </ul>
    </div>

    <!-- Pagination -->
    @if($borrowings->hasPages())
        <div class="mt-6">
            {{ $borrowings->links() }}
        </div>
    @endif
</div>
@endsection