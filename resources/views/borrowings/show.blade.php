@extends('layouts.dashboard')

@section('title', 'Detail Peminjaman')
@section('page-title', 'Detail Peminjaman')
@section('page-description', 'Informasi lengkap tentang peminjaman')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-exchange-alt text-2xl"></i>
                    <div>
                        <h1 class="text-2xl font-bold">Detail Peminjaman</h1>
                        <p class="text-primary-100">ID: #{{ $borrowing->id }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($borrowing->status === 'dipinjam') bg-green-100 text-green-800
                        @elseif($borrowing->status === 'dikembalikan') bg-gray-100 text-gray-800
                        @else bg-red-100 text-red-800 @endif">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ $borrowing->status_text }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Borrowing Details -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Peminjaman</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Peminjam</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->user->name }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Role</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ ucfirst($borrowing->user->role) }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Buku</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->book->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Penulis</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->book->author }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tanggal Pinjam</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->borrow_date->format('d M Y') }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Jatuh Tempo</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->due_date->format('d M Y') }}</dd>
                                </div>
                                @if($borrowing->return_date)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tanggal Kembali</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->return_date->format('d M Y') }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Status</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $borrowing->status_text }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Fine Information -->
                        @if($borrowing->fine_amount > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Denda</h3>
                                <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                        <div>
                                            <dt class="text-sm font-medium text-red-700">Jumlah Denda</dt>
                                            <dd class="mt-1 text-lg font-bold text-red-900">Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}</dd>
                                        </div>
                                        <div>
                                            <dt class="text-sm font-medium text-red-700">Status Pembayaran</dt>
                                            <dd class="mt-1">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                    @if($borrowing->fine_paid) bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800 @endif">
                                                    {{ $borrowing->fine_paid ? 'Lunas' : 'Belum Lunas' }}
                                                </span>
                                            </dd>
                                        </div>
                                        @if($borrowing->fine_calculated_at)
                                            <div>
                                                <dt class="text-sm font-medium text-red-700">Dihitung Pada</dt>
                                                <dd class="mt-1 text-sm text-red-900">{{ $borrowing->fine_calculated_at->format('d M Y H:i') }}</dd>
                                            </div>
                                        @endif
                                    </dl>
                                </div>
                            </div>
                        @endif

                        <!-- Notes -->
                        @if($borrowing->notes)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Catatan</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <p class="text-sm text-gray-700">{{ $borrowing->notes }}</p>
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
                            @php
                                $printRoute = auth()->user()->isPerpustakawan() ?
                                    route('perpustakawan.borrowings.print-receipt', $borrowing) :
                                    (auth()->user()->isGuru() ?
                                        route('guru.borrowings.print-receipt', $borrowing) :
                                        route('siswa.borrowings.print-receipt', $borrowing));
                            @endphp
                            <a href="{{ $printRoute }}"
                               target="_blank"
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <i class="fas fa-print mr-2"></i>
                                Cetak Struk
                            </a>

                            @if($borrowing->status === 'dipinjam')
                                @if(auth()->user()->isPerpustakawan())
                                    <a href="{{ route('perpustakawan.borrowings.edit', $borrowing) }}" 
                                       class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                        <i class="fas fa-edit mr-2"></i>
                                        Edit Peminjaman
                                    </a>
                                @endif
                                
                                @if(auth()->user()->isPerpustakawan() || $borrowing->user_id === auth()->id())
                                    @php
                                        $returnRoute = auth()->user()->isPerpustakawan() ? 
                                            route('perpustakawan.borrowings.return', $borrowing) : 
                                            (auth()->user()->isGuru() ? 
                                                route('guru.borrowings.return', $borrowing) : 
                                                route('siswa.borrowings.return', $borrowing));
                                    @endphp
                                    <form method="POST" action="{{ $returnRoute }}" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin mengembalikan buku ini?')">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" 
                                                class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                            <i class="fas fa-undo mr-2"></i>
                                            Kembalikan Buku
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
                                      onsubmit="return confirm('Apakah Anda yakin ingin membayar denda Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}?')">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">
                                        <i class="fas fa-credit-card mr-2"></i>
                                        Bayar Denda
                                    </button>
                                </form>
                            @endif
                            
                            <a href="{{ url()->previous() }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection