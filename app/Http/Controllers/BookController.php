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
                  ->orWhere('isbn', 'like', '%' . $search . '%');
            });
        }

        $books = $query->latest()->paginate(10)->withQueryString();
        return view('books.index', compact('books'));
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
        $request->file('photo')->storeAs('public/images', $photoName);
    }



    Book::create([
        'title' => $request->title,
        'author' => $request->author,
        'isbn' => $request->isbn,
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
            if ($book->photo) {
                Storage::delete('public/images' . $book->photo);
            }

            $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
            $request->file('photo')->storeAs('public/images', $photoName);
        }



        $book->update([
            'title' => $request->title,
            'author' => $request->author,
            'isbn' => $request->isbn,
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
        // Delete photos if exist
        if ($book->photo) {
            Storage::delete('public/images/' . $book->photo);
        }
        if ($book->cover_image) {
            Storage::delete('public/images/' . $book->cover_image);
        }

        $book->delete();

        return redirect()->route('perpustakawan.books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }
}