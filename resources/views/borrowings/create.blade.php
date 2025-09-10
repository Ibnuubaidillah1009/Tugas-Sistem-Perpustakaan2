@extends('layouts.dashboard')

@section('title', 'Tambah Peminjaman')
@section('page-title', 'Tambah Peminjaman')
@section('page-description', 'Catat peminjaman buku baru')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white shadow rounded-lg">
        <div class="px-4 py-5 sm:p-6">
            <form method="POST" action="{{ route('perpustakawan.borrowings.store') }}" class="space-y-6">
                @csrf
                
                <div class="grid grid-cols-1 gap-6">
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

                    <div>
                        <label for="book_id" class="block text-sm font-medium text-gray-700">
                            Buku <span class="text-red-500">*</span>
                        </label>
                        <select name="book_id" id="book_id" required
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('book_id') border-red-500 @enderror">
                            <option value="">Pilih buku</option>
                            @foreach($books as $book)
                                <option value="{{ $book->id }}" {{ old('book_id') == $book->id ? 'selected' : '' }}
                                        data-stock="{{ $book->stock }}">
                                    {{ $book->title }} - {{ $book->author }} (Stok: {{ $book->stock }})
                                </option>
                            @endforeach
                        </select>
                        @error('book_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="due_date" class="block text-sm font-medium text-gray-700">
                            Tanggal Dikembalikan <span class="text-red-500">*</span>
                        </label>
                        <input type="date" name="due_date" id="due_date" required
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm @error('due_date') border-red-500 @enderror"
                               value="{{ old('due_date') }}">
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
document.getElementById('book_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const stock = selectedOption.getAttribute('data-stock');
    
    if (stock && parseInt(stock) <= 0) {
        alert('Buku yang dipilih tidak tersedia (stok habis).');
        this.value = '';
    }
});
</script>
@endsection