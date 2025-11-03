@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman')
@section('page-description', 'Catat peminjaman buku baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route(auth()->user()->isPerpustakawan() ? 'perpustakawan.borrowings.store' : (auth()->user()->isGuru() ? 'guru.borrowings.store' : 'siswa.borrowings.store')) }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
                    @if(auth()->user()->isPerpustakawan())
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">
                            Peminjam <span class="text-red-500">*</span>
                        </label>
                        <select name="user_id" id="user_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('user_id') border-red-500 @enderror">
                            <option value="">Pilih peminjam</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ ucfirst($user->role) }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    @endif

                    <div>
                        <label for="book_search" class="block text-sm font-medium text-gray-700">
                            Cari Buku (Judul, Penulis, atau Barcode) <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="book_search" name="book_search" autocomplete="off" placeholder="Cari buku dengan judul, penulis, atau barcode"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('book_id') border-red-500 @enderror" />
                        <input type="hidden" id="book_id" name="book_id" value="{{ old('book_id') }}" required />
                        <div id="book_search_results" class="border border-gray-300 rounded-md mt-1 max-h-48 overflow-y-auto hidden bg-white z-10 absolute w-full"></div>
                        @error('book_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">
                            Tanggal Dikembalikan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" readonly
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-100 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('due_date') border-red-500 @enderror"
                               value="{{ date('Y-m-d', strtotime('+7 days')) }}">
                        @error('due_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">
                            Catatan
                        </label>
                        <textarea name="notes" id="notes" rows="3"
                                  class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('notes') border-red-500 @enderror"
                                  placeholder="Catatan tambahan tentang peminjaman">{{ old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex justify-end space-x-3">
                    <a href="{{ route('perpustakawan.borrowings.index') }}" 
                       class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Batal
                    </a>
                    <button type="submit" 
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Peminjaman
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const bookSearch = document.getElementById('book_search');
    const bookId = document.getElementById('book_id');
    const resultsContainer = document.getElementById('book_search_results');
    let searchTimeout;

    bookSearch.addEventListener('input', function() {
        clearTimeout(searchTimeout);
        const query = this.value.trim();

        if (query.length < 2) {
            resultsContainer.classList.add('hidden');
            return;
        }

        searchTimeout = setTimeout(() => {
            fetch(`/api/books/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsContainer.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(book => {
                            const div = document.createElement('div');
                            div.className = 'p-2 hover:bg-gray-100 cursor-pointer border-b border-gray-200';
                            div.innerHTML = `
                                <div class="flex items-center space-x-3">
                                    <img src="${book.photo_url}" alt="${book.title}" class="w-12 h-16 object-cover rounded border border-gray-300" />
                                    <div>
                                        <div class="font-medium">${book.title}</div>
                                        <div class="text-sm text-gray-600">${book.author}</div>
                                        <div class="text-xs text-gray-500">Stok: ${book.available_stock}</div>
                                    </div>
                                </div>
                            `;
                            div.addEventListener('click', function() {
                                bookSearch.value = `${book.title} - ${book.author}`;
                                bookId.value = book.id;
                                resultsContainer.classList.add('hidden');
                            });
                            resultsContainer.appendChild(div);
                        });
                        resultsContainer.classList.remove('hidden');
                    } else {
                        resultsContainer.innerHTML = '<div class="p-2 text-gray-500">Tidak ada buku ditemukan</div>';
                        resultsContainer.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                });
        }, 300);
    });

    // Hide results when clicking outside
    document.addEventListener('click', function(e) {
        if (!bookSearch.contains(e.target) && !resultsContainer.contains(e.target)) {
            resultsContainer.classList.add('hidden');
        }
    });
});
</script>
@endsection