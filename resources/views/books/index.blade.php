@extends('layouts.dashboard')

@section('title', 'Daftar Buku')
@section('page-title', 'Daftar Buku')
@section('page-description', 'Lihat koleksi buku yang tersedia di perpustakaan')

@section('content')
<div class="space-y-6">
	<!-- Actions & Search -->
	<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
		<form method="GET" action="{{ url()->current() }}" class="w-full sm:max-w-md">
			<div class="relative">
				<input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, penulis, ISBN, atau barcode..."
					class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-primary-500 focus:border-primary-500">
				<span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
					<i class="fas fa-search"></i>
				</span>
			</div>
		</form>

		<div class="flex items-center gap-2">
			@if(auth()->user()->isPerpustakawan())
				<a href="{{ route('perpustakawan.books.create') }}"
					class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
					<i class="fas fa-plus mr-2"></i>
					Tambah Buku 
				</a>
			@endif
		</div>
	</div>

	<!-- Books Grid -->
	<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
		@forelse($books as $book)
			@php
				$role = auth()->user()->role;
				$showRoute = $role === 'perpustakawan'
					? route('perpustakawan.books.show', $book)
					: ($role === 'guru'
						? route('guru.books.show', $book)
						: route('siswa.books.show', $book));
						
				$coverUrl = $book->photo_url;

				$canBorrow = (auth()->user()->isGuru() || auth()->user()->isSiswa());
				$borrowRoute = auth()->user()->isGuru()
					? route('guru.borrowings.store')
					: route('siswa.borrowings.store');
				$available = property_exists($book, 'available_stock') || isset($book->available_stock)
					? $book->available_stock
					: (int) $book->stock;
			@endphp

			<div class="bg-white rounded-lg shadow hover:shadow-lg transition-shadow duration-200 overflow-hidden flex flex-col">
				<a href="{{ $showRoute }}" class="block">
					<div class="aspect-w-3 aspect-h-4 bg-gray-100">
						<img src="{{ $coverUrl }}" alt="{{ $book->title }} cover"
							class="w-full h-64 object-cover">
					</div>
				</a>

				<div class="p-4 flex-1 flex flex-col">
					<div class="flex-1">
						<h3 class="text-base font-semibold text-gray-900 leading-tight">
							<a href="{{ $showRoute }}" class="hover:text-primary-600">
								{{ $book->title }}
							</a>
						</h3>
						<p class="text-sm text-gray-600 mt-1">{{ $book->publisher ?? 'Tidak ada penerbit' }}</p>
						<p class="text-xs text-gray-500 mt-1">ISBN: {{ $book->isbn }}</p>
					</div>

					<div class="mt-4 flex items-center justify-between">
						<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
							{{ $available > 0 ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
							{{ $available > 0 ? 'Tersedia: ' . $available : 'Stok Habis' }}
						</span>

						<div class="flex items-center gap-2">
							@if(auth()->user()->isPerpustakawan())
								<a href="{{ route('perpustakawan.books.edit', $book) }}"
									class="text-yellow-600 hover:text-yellow-700" title="Edit">
									<i class="fas fa-edit"></i>
								</a>
								@php
									$borrowedCount = $book->borrowings()->where('status', 'dipinjam')->count();
								@endphp
								@if($borrowedCount > 0)
									<button onclick="showDeleteConfirmation({{ $book->id }}, '{{ $book->title }}', {{ $borrowedCount }})"
										class="text-red-600 hover:text-red-700" title="Hapus">
										<i class="fas fa-trash"></i>
									</button>
								@else
									<form method="POST" action="{{ route('perpustakawan.books.destroy', $book) }}" onsubmit="return confirm('Hapus buku: {{ $book->title }}?')" class="inline">
										@csrf
										@method('DELETE')
										<button type="submit" class="text-red-600 hover:text-red-700" title="Hapus">
											<i class="fas fa-trash"></i>
										</button>
									</form>
								@endif
							@endif

							@if($canBorrow)
								@if($available > 0)
									<form method="POST" action="{{ $borrowRoute }}"
										onsubmit="return confirm('Pinjam buku: {{ $book->title }}?')">
										@csrf
										<input type="hidden" name="book_id" value="{{ $book->id }}">
										<button type="submit"
											class="inline-flex items-center px-3 py-1.5 rounded text-xs font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
											<i class="fas fa-book-reader mr-1"></i>
											Pinjam
										</button>
									</form>
								@else
									<span class="text-xs text-red-500">Tidak tersedia</span>
								@endif
							@endif
						</div>
					</div>
				</div>
			</div>
		@empty
			<div class="col-span-full">
				<div class="bg-white rounded-lg border border-gray-200 p-10 text-center">
					<i class="fas fa-book-open text-3xl text-gray-400"></i>
					<p class="mt-3 text-gray-600">Belum ada buku yang tersedia.</p>
					@if(auth()->user()->isPerpustakawan())
						<a href="{{ route('perpustakawan.books.create') }}" class="mt-4 inline-flex items-center px-4 py-2 rounded text-sm font-medium text-white bg-primary-600 hover:bg-primary-700">
							<i class="fas fa-plus mr-2"></i> Tambah Buku
						</a>
					@endif
				</div>
			</div>
		@endforelse
	</div>

	<!-- Pagination -->
	@if(method_exists($books, 'links'))
		<div>
			{{ $books->withQueryString()->links() }}
		</div>
	@endif

	<!-- Delete Confirmation Modal -->
	<div id="deleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
		<div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
			<div class="mt-3 text-center">
				<div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
					<i class="fas fa-exclamation-triangle text-red-600"></i>
				</div>
				<h3 class="text-lg leading-6 font-medium text-gray-900 mt-4" id="modalTitle">Konfirmasi Hapus</h3>
				<div class="mt-2 px-7 py-3">
					<p class="text-sm text-gray-500" id="modalMessage"></p>
				</div>
				<div class="flex items-center px-4 py-3">
					<button id="cancelBtn" class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md w-full mr-2 shadow-sm hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300">
						Tidak
					</button>
					<button id="confirmBtn" class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md w-full shadow-sm hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-300">
						Ya, Hapus
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
function showDeleteConfirmation(bookId, bookTitle, borrowedCount) {
    document.getElementById('modalTitle').textContent = 'Konfirmasi Hapus Buku';
    document.getElementById('modalMessage').innerHTML = `Buku "<strong>${bookTitle}</strong>" sedang dipinjam oleh <strong>${borrowedCount} orang</strong>.<br><br>Apakah Anda yakin ingin menghapus buku ini?<br><small class="text-gray-500">Peminjaman akan tetap ada dalam sistem.</small>`;

    document.getElementById('confirmBtn').onclick = function() {
        // Disable button to prevent double-click
        this.disabled = true;
        this.textContent = 'Menghapus...';

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = `/perpustakawan/books/${bookId}`;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = csrfToken;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        const forceDeleteInput = document.createElement('input');
        forceDeleteInput.type = 'hidden';
        forceDeleteInput.name = 'force_delete';
        forceDeleteInput.value = '1';
        form.appendChild(forceDeleteInput);

        document.body.appendChild(form);
        form.submit();
    };

    document.getElementById('cancelBtn').onclick = function() {
        // Reset button state
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Ya, Hapus';

        document.getElementById('deleteModal').classList.add('hidden');
    };

    document.getElementById('deleteModal').classList.remove('hidden');
}

// Close modal when clicking outside
document.getElementById('deleteModal').addEventListener('click', function(e) {
    if (e.target === this) {
        // Reset button state
        const confirmBtn = document.getElementById('confirmBtn');
        confirmBtn.disabled = false;
        confirmBtn.textContent = 'Ya, Hapus';

        this.classList.add('hidden');
    }
});
</script>
@endsection
