@extends('layouts.dashboard')

@section('title', 'Tambah Buku')
@section('page-title', 'Tambah Buku')
@section('page-description', 'Tambahkan buku baru ke dalam koleksi perpustakaan')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <!-- Header -->
        <div class="px-6 py-4 bg-gradient-to-r from-primary-600 to-primary-700 text-white">
            <div class="flex items-center space-x-3">
                <i class="fas fa-plus-circle text-2xl"></i>
                <div>
                    <h1 class="text-2xl font-bold">Tambah Buku Baru</h1>
                    <p class="text-primary-100">Lengkapi informasi buku untuk ditambahkan ke koleksi</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('perpustakaan.books.store') }}" enctype="multipart/form-data" class="p-6 space-y-6">
            @csrf
            
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Left Column - Basic Info -->
                <div class="space-y-6">
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            Judul Buku <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('title') border-red-500 @enderror"
                               placeholder="Masukkan judul buku">
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="author" class="block text-sm font-medium text-gray-700 mb-2">
                            Penulis <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('author') border-red-500 @enderror"
                               placeholder="Masukkan nama penulis">
                        @error('author')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="isbn" class="block text-sm font-medium text-gray-700 mb-2">
                            ISBN <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="isbn" name="isbn" value="{{ old('isbn') }}" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('isbn') border-red-500 @enderror"
                               placeholder="Masukkan ISBN buku">
                        @error('isbn')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="publisher" class="block text-sm font-medium text-gray-700 mb-2">
                            Penerbit
                        </label>
                        <input type="text" id="publisher" name="publisher" value="{{ old('publisher') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('publisher') border-red-500 @enderror"
                               placeholder="Masukkan nama penerbit">
                        @error('publisher')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori
                        </label>
                        <select id="category" name="category"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('category') border-red-500 @enderror">
                            <option value="">Pilih kategori</option>
                            <option value="Fiksi" {{ old('category') == 'Fiksi' ? 'selected' : '' }}>Fiksi</option>
                            <option value="Non-Fiksi" {{ old('category') == 'Non-Fiksi' ? 'selected' : '' }}>Non-Fiksi</option>
                            <option value="Pendidikan" {{ old('category') == 'Pendidikan' ? 'selected' : '' }}>Pendidikan</option>
                            <option value="Teknologi" {{ old('category') == 'Teknologi' ? 'selected' : '' }}>Teknologi</option>
                            <option value="Sejarah" {{ old('category') == 'Sejarah' ? 'selected' : '' }}>Sejarah</option>
                            <option value="Sains" {{ old('category') == 'Sains' ? 'selected' : '' }}>Sains</option>
                            <option value="Agama" {{ old('category') == 'Agama' ? 'selected' : '' }}>Agama</option>
                            <option value="Lainnya" {{ old('category') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="publication_year" class="block text-sm font-medium text-gray-700 mb-2">
                            Tahun Terbit
                        </label>
                        <input type="number" id="publication_year" name="publication_year" value="{{ old('publication_year') }}"
                               min="1900" max="{{ date('Y') }}"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('publication_year') border-red-500 @enderror"
                               placeholder="Masukkan tahun terbit">
                        @error('publication_year')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Stok <span class="text-red-500">*</span>
                        </label>
                        <input type="number" id="stock" name="stock" value="{{ old('stock', 1) }}" required min="0"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('stock') border-red-500 @enderror"
                               placeholder="Masukkan jumlah stok">
                        @error('stock')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Right Column - Images and Description -->
                <div class="space-y-6">
                    <!-- Book Photo -->
                    <div>
                        <label for="photo" class="block text-sm font-medium text-gray-700 mb-2">
                            Foto Buku
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-camera text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="photo" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                        <span>Upload foto buku</span>
                                        <input id="photo" name="photo" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'photo-preview')">
                                    </label>
                                    <p class="pl-1">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        </div>
                        <div id="photo-preview" class="mt-2 hidden">
                            <img id="photo-preview-img" class="w-full h-48 object-cover rounded-md">
                        </div>
                        @error('photo')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Cover Image -->
                    <div>
                        <label for="cover_image" class="block text-sm font-medium text-gray-700 mb-2">
                            Cover Buku
                        </label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md hover:border-gray-400 transition-colors">
                            <div class="space-y-1 text-center">
                                <i class="fas fa-image text-4xl text-gray-400"></i>
                                <div class="flex text-sm text-gray-600">
                                    <label for="cover_image" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500">
                                        <span>Upload cover buku</span>
                                        <input id="cover_image" name="cover_image" type="file" accept="image/*" class="sr-only" onchange="previewImage(this, 'cover-preview')">
                                    </label>
                                    <p class="pl-1">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, GIF hingga 2MB</p>
                            </div>
                        </div>
                        <div id="cover-preview" class="mt-2 hidden">
                            <img id="cover-preview-img" class="w-full h-48 object-cover rounded-md">
                        </div>
                        @error('cover_image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                            Deskripsi Buku
                        </label>
                        <textarea id="description" name="description" rows="6"
                                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror"
                                  placeholder="Masukkan deskripsi singkat tentang buku">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200">
                <a href="{{ route('perpustakaan.books.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <i class="fas fa-times mr-2"></i>
                    Batal
                </a>
                <button type="submit" 
                        class="px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition duration-150 ease-in-out">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Buku
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewImage(input, previewId) {
    const file = input.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById(previewId);
            const previewImg = document.getElementById(previewId + '-img');
            previewImg.src = e.target.result;
            preview.classList.remove('hidden');
        };
        reader.readAsDataURL(file);
    }
}
</script>
@endsection