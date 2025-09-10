@extends('layouts.dashboard')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')
@section('page-description', 'Informasi lengkap tentang buku')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-book text-2xl"></i>
                    <div>
                        <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
                        <p class="text-primary-100">oleh {{ $book->author }}</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                        @if($book->stock > 0) bg-green-100 text-green-800
                        @else bg-red-100 text-red-800 @endif">
                        <i class="fas fa-circle text-xs mr-2"></i>
                        {{ $book->stock > 0 ? 'Tersedia' : 'Habis' }}
                    </span>
                </div>
            </div>
        </div>

        <div class="px-6 py-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Book Details -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Basic Info -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Buku</h3>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->isbn }}</dd>
                                </div>
                                @if($book->category)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $book->category }}</dd>
                                    </div>
                                @endif
                                @if($book->publication_year)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Tahun Terbit</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $book->publication_year }}</dd>
                                    </div>
                                @endif
                                @if($book->publisher)
                                    <div>
                                        <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $book->publisher }}</dd>
                                    </div>
                                @endif
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Stok Tersedia</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->stock }} buku</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Dibuat</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->created_at->format('d M Y H:i') }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Description -->
                        @if($book->description)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi</h3>
                                <p class="text-sm text-gray-700 leading-relaxed">{{ $book->description }}</p>
                            </div>
                        @endif

                        <!-- Borrowing History -->
                        @if($book->borrowings->count() > 0)
                            <div>
                                <h3 class="text-lg font-medium text-gray-900 mb-4">Riwayat Peminjaman</h3>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <div class="flow-root">
                                        <ul class="-my-5 divide-y divide-gray-200">
                                            @foreach($book->borrowings->take(5) as $borrowing)
                                                <li class="py-3">
                                                    <div class="flex items-center space-x-4">
                                                        <div class="flex-shrink-0">
                                                            <i class="fas fa-user text-gray-400"></i>
                                                        </div>
                                                        <div class="flex-1 min-w-0">
                                                            <p class="text-sm font-medium text-gray-900">
                                                                {{ $borrowing->user->name }}
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
                                    @if($book->borrowings->count() > 5)
                                        <p class="mt-3 text-sm text-gray-500">
                                            Dan {{ $book->borrowings->count() - 5 }} peminjaman lainnya...
                                        </p>
                                    @endif
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
                            @if(auth()->user()->isPerpustakawan())
                                <a href="{{ route('perpustakawan.books.edit', $book) }}" 
                                   class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                    <i class="fas fa-edit mr-2"></i>
                                    Edit Buku
                                </a>
                                
                                <form method="POST" action="{{ route('perpustakawan.books.destroy', $book) }}" 
                                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus buku ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                        <i class="fas fa-trash mr-2"></i>
                                        Hapus Buku
                                    </button>
                                </form>
                            @elseif(auth()->user()->isGuru() || auth()->user()->isSiswa())
                                @if($book->stock > 0)
                                    <button onclick="openBorrowModal()" 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                        <i class="fas fa-plus mr-2"></i>
                                        Pinjam Buku
                                    </button>
                                @else
                                    <button disabled 
                                            class="w-full inline-flex items-center justify-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-gray-400 cursor-not-allowed">
                                        <i class="fas fa-times mr-2"></i>
                                        Stok Habis
                                    </button>
                                @endif
                            @endif
                            
                            <a href="{{ url()->previous() }}" 
                               class="w-full inline-flex items-center justify-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                                <i class="fas fa-arrow-left mr-2"></i>
                                Kembali
                            </a>
                        </div>

                        <!-- Book Stats -->
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h4 class="text-sm font-medium text-gray-900 mb-3">Statistik</h4>
                            <dl class="space-y-2">
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Total Peminjaman</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $book->borrowings->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Sedang Dipinjam</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $book->borrowings->where('status', 'borrowed')->count() }}</dd>
                                </div>
                                <div class="flex justify-between">
                                    <dt class="text-sm text-gray-500">Tersedia</dt>
                                    <dd class="text-sm font-medium text-gray-900">{{ $book->available_stock }}</dd>
                                </div>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Borrow Modal -->
@if(auth()->user()->isGuru() || auth()->user()->isSiswa())
<div id="borrowModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium text-gray-900">Pinjam Buku</h3>
                <button onclick="closeBorrowModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            
            <form method="POST" action="{{ route('guru.borrowings.store') }}">
                @csrf
                <input type="hidden" name="book_id" value="{{ $book->id }}">
                
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Buku</label>
                    <p class="text-sm text-gray-900">{{ $book->title }}</p>
                    <p class="text-xs text-gray-500">oleh {{ $book->author }}</p>
                </div>
                
                <div class="mb-4">
                    <label for="due_date" class="block text-sm font-medium text-gray-700 mb-2">
                        Tanggal Jatuh Tempo
                    </label>
                    <input type="date" name="due_date" id="due_date" required
                           min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500">
                </div>
                
                <div class="mb-4">
                    <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Catatan (Opsional)
                    </label>
                    <textarea name="notes" id="notes" rows="3"
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-primary-500 focus:border-primary-500"
                              placeholder="Catatan tambahan..."></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeBorrowModal()"
                            class="px-4 py-2 border border-gray-300 rounded-md text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Batal
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-primary-600 text-white rounded-md text-sm font-medium hover:bg-primary-700">
                        Pinjam
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function openBorrowModal() {
    document.getElementById('borrowModal').classList.remove('hidden');
}

function closeBorrowModal() {
    document.getElementById('borrowModal').classList.add('hidden');
}
</script>
@endif
@endsection