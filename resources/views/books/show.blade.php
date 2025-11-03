@extends('layouts.dashboard')

@section('title', 'Detail Buku')
@section('page-title', 'Detail Buku')
@section('page-description', 'Informasi lengkap buku')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center space-x-3">
                <i class="fas fa-book text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">{{ $book->title }}</h1>
                    <p class="text-primary-100">oleh {{ $book->author }}</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Book Images -->
                <div class="lg:col-span-1">
                    <div class="space-y-6">
                        <!-- Book Photo -->
                        @if($book->photo)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Foto Buku
                                </label>
                                <div class="flex justify-center">
                                    <img src="{{ $book->photo_url }}" alt="{{ $book->title }}"
                                         class="w-full h-64 object-cover rounded-lg border-4 border-white shadow-lg">
                                </div>
                            </div>
                        @endif

                        
                        <!-- Cover Image -->
                        {{-- Cover image removed as per request --}}
                        {{-- @if($book->cover_image)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    Cover Buku
                                </label>
                                <div class="flex justify-center">
                                    <img src="{{ $book->cover_image_url }}" alt="{{ $book->title }} Cover"
                                         class="w-full h-64 object-cover rounded-lg border-4 border-white shadow-lg">
                                </div>
                            </div>
                        @endif --}}

                        <!-- Stock Status -->
                        <div class="bg-gray-50 rounded-lg p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Status Stok</h3>
                            <div class="flex items-center justify-between">
                                <span class="text-sm text-gray-500">Stok Tersedia:</span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    {{ $book->stock > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $book->stock }} {{ $book->stock > 1 ? 'buku' : 'buku' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Book Information -->
                <div class="lg:col-span-2">
                    <div class="space-y-6">
                        <!-- Basic Information -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                            <dl class="grid grid-cols-1 gap-x-4 gap-y-4 sm:grid-cols-2">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Judul</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->title }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Penulis</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->author }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->isbn }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->publisher ?? 'Tidak ada informasi' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tahun Terbit</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->publication_year ?? 'Tidak ada informasi' }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Kategori</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $book->category ?? 'Tidak ada kategori' }}</dd>
                                </div>
                            </dl>
                        </div>

                        <!-- Description -->
                        @if($book->description)
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $book->description }}</p>
                        </div>
                        @endif

                        <!-- Borrowing Statistics -->
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Statistik Peminjaman</h3>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-primary-600">{{ $book->borrowings()->count() }}</div>
                                    <div class="text-sm text-gray-500">Total Peminjaman</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-green-600">{{ $book->borrowings()->where('status', 'dikembalikan')->count() }}</div>
                                    <div class="text-sm text-gray-500">Sudah Dikembalikan</div>
                                </div>
                                <div class="text-center">
                                    <div class="text-2xl font-bold text-yellow-600">{{ $book->borrowings()->where('status', 'dipinjam')->count() }}</div>
                                    <div class="text-sm text-gray-500">Sedang Dipinjam</div>
                                </div>
                            </div>
                        </div>

                        <!-- Borrow Book (for guru and siswa) -->
                        @if(auth()->user()->isGuru() || auth()->user()->isSiswa())
                            @if($book->stock > 0)
                                <div class="bg-green-50 rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-green-900 mb-4">Pinjam Buku</h3>
                                    <p class="text-sm text-green-700 mb-4">Buku ini tersedia untuk dipinjam. Klik tombol di bawah untuk meminjam buku ini.</p>
                                    <form method="POST" action="{{ route(auth()->user()->isGuru() ? 'guru.borrowings.store' : 'siswa.borrowings.store') }}">
                                        @csrf
                                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                                            <i class="fas fa-plus mr-2"></i>
                                            Pinjam Buku Ini
                                        </button>
                                    </form>
                                </div>
                            @else
                                <div class="bg-red-50 rounded-lg p-6">
                                    <h3 class="text-lg font-medium text-red-900 mb-4">Buku Tidak Tersedia</h3>
                                    <p class="text-sm text-red-700">Maaf, buku ini sedang tidak tersedia untuk dipinjam karena stok habis.</p>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                @if(auth()->user()->isPerpustakawan())
                    <a href="{{ route('perpustakawan.books.edit', $book) }}"
                       class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        <i class="fas fa-edit mr-2"></i>
                        Edit Buku
                    </a>
                @else
                    <a href="{{ route(auth()->user()->isGuru() ? 'guru.books.index' : 'siswa.books.index') }}"
                       class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Daftar Buku
                    </a>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
