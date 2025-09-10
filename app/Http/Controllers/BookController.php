<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::latest()->paginate(10);
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
        'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
    ]);

    $photoName = null;
    if ($request->hasFile('photo')) {
        $photoName = time() . '_' . $request->file('photo')->getClientOriginalName();
        $request->file('photo')->storeAs('public/photos/books', $photoName);
    }

    $coverName = null;
    if ($request->hasFile('cover_image')) {
        $coverName = time() . '_cover_' . $request->file('cover_image')->getClientOriginalName();
        $request->file('cover_image')->storeAs('public/photos/books/covers', $coverName);
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
        'cover_image' => $coverName,
    ]);

    return redirect()->route('perpustakaan.books.index')
        ->with('success', 'Buku berhasil ditambahkan.');
    }
}