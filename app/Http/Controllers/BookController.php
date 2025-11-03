<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $query = Book::query();

        // Handle search query
        if ($search = request('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%')
                  ->orWhere('isbn', 'like', '%' . $search . '%')
                  ->orWhere('barcode', 'like', '%' . $search . '%');
            });
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        return view('books.index', compact('books'));
    }

    // New method for AJAX book search
    public function search(Request $request)
    {
        $q = $request->query('q', '');

        $books = Book::withCount(['borrowings' => function ($query) {
            $query->where('status', 'borrowed');
        }])
        ->havingRaw('stock - borrowings_count > 0')
        ->where(function ($query) use ($q) {
            $query->where('title', 'like', "%{$q}%")
                  ->orWhere('author', 'like', "%{$q}%")
                  ->orWhere('barcode', 'like', "%{$q}%");
        })
        ->get(['id', 'title', 'author', 'stock', 'photo']);

        $books->transform(function ($book) {
            $book->available_stock = $book->stock - $book->borrowings_count;
            $book->photo_url = $book->photo_url;
            unset($book->borrowings_count);
            return $book;
        });

        return response()->json($books);
    }

    public function create()
    {
        return view('books.create');
    }

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'author' => 'required|string|max:255',
        'isbn' => 'required|string|unique:books',
        'barcode' => 'nullable|string|unique:books',
        'description' => 'nullable|string',
        'stock' => 'required|integer|min:0',
        'category' => 'nullable|string|max:255',
        'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
        'publisher' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $photoName = null;
    if ($request->hasFile('photo')) {
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->move(public_path('images'), $photoName);
    }



    Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
        'barcode' => $request->barcode,
        'description' => $request->description,
        'stock' => $request->stock,
        'category' => $request->category,
        'publication_year' => $request->publication_year,
        'publisher' => $request->publisher,
        'photo' => $photoName,
    ]);

    return redirect()->route('perpustakawan.books.index')
        ->with('success', 'Buku berhasil ditambahkan.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(Request $request, Book $book)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'barcode' => 'nullable|string|unique:books,barcode,' . $book->id,
            'description' => 'nullable|string',
            'stock' => 'required|integer|min:0',
            'category' => 'nullable|string|max:255',
            'publication_year' => 'nullable|integer|min:1900|max:' . date('Y'),
            'publisher' => 'nullable|string|max:255',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $photoName = $book->photo;
        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($book->photo && file_exists(public_path('images/' . $book->photo))) {
                unlink(public_path('images/' . $book->photo));
            }

            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->move(public_path('images'), $photoName);
        }



        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
            'barcode' => $request->barcode,
            'description' => $request->description,
            'stock' => $request->stock,
            'category' => $request->category,
            'publication_year' => $request->publication_year,
            'publisher' => $request->publisher,
            'photo' => $photoName,
        ]);

        return redirect()->route('perpustakawan.books.index')
            ->with('success', 'Buku berhasil diperbarui.');
    }

    public function destroy(Book $book)
    {
        // Check if book is currently being borrowed
        $activeBorrowings = $book->borrowings()->where('status', 'dipinjam')->count();

        $forceDelete = request('force_delete', false);

        if ($activeBorrowings > 0 && !$forceDelete) {
            // Return to show confirmation dialog
            return redirect()->back()
                ->with('warning', "Buku '{$book->title}' sedang dipinjam oleh {$activeBorrowings} orang. Apakah Anda yakin ingin menghapus buku ini? Peminjaman akan tetap ada.")
                ->with('book_to_delete', $book->id);
        }

        // Delete photos if exist
        if ($book->photo && file_exists(public_path('images/' . $book->photo))) {
            unlink(public_path('images/' . $book->photo));
        }
        if ($book->cover_image && file_exists(public_path('images/' . $book->cover_image))) {
            unlink(public_path('images/' . $book->cover_image));
        }

        // For force delete, we need to manually delete since the model boot method prevents it
        if ($forceDelete && $activeBorrowings > 0) {
            // Delete the book directly from database, bypassing the model boot method
            \DB::table('books')->where('id', $book->id)->delete();

            return redirect()->route('perpustakawan.books.index')
                ->with('success', 'Buku berhasil dihapus. Peminjaman tetap ada dalam sistem.');
        }

        // Normal delete for books without active borrowings
        $book->delete();

        return redirect()->route('perpustakawan.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}