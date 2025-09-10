<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Borrowing;
use App\Models\Book;
use App\Models\User;
use Carbon\Carbon;

class BorrowingController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Update fines for overdue borrowings
        $this->updateOverdueFines();
        
        if ($user->isPerpustakawan()) {
            $borrowings = Borrowing::with(['user', 'book'])
                ->latest()
                ->paginate(10);
        } else {
            $borrowings = Borrowing::with('book')
                ->where('user_id', $user->id)
                ->latest()
                ->paginate(10);
        }

        return view('borrowings.index', compact('borrowings'));
    }

public function create()
{
    $user = auth()->user();
    
    if ($user->isPerpustakaan()) {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::where('role', '!=', 'perpustakaan')->get();
        return view('borrowings.create', compact('books', 'users'));
    } else {
        // For guru and siswa, show available books
        $books = Book::where('stock', '>', 0)->get();
        return view('borrowings.create', compact('books'));
    }
}

public function store(Request $request)
{
    $user = auth()->user();
    
    if ($user->isPerpustakaan()) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'due_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);
        
        $userId = $request->user_id;
    } else {
        $request->validate([
            'book_id' => 'required|exists:books,id',
            'notes' => 'nullable|string',
        ]);
        
        $userId = $user->id;
    }

    $book = Book::findOrFail($request->book_id);
    
    if ($book->stock <= 0) {
        return back()->with('error', 'Buku tidak tersedia.');
    }

    // Check if user already has this book borrowed
    $existingBorrowing = Borrowing::where('user_id', $userId)
        ->where('book_id', $book->id)
        ->where('status', 'borrowed')
        ->first();

    if ($existingBorrowing) {
        return back()->with('error', 'Anda sudah meminjam buku ini.');
    }

    Borrowing::create([
        'user_id' => $userId,
        'book_id' => $book->id,
        'borrow_date' => now(),
        'due_date' => $request->due_date ?? now()->addDays(7),
        'status' => 'borrowed',
        'notes' => $request->notes,
    ]);

    // Decrease book stock
    $book->decrement('stock');

    $redirectRoute = $user->isPerpustakaan() ? 'perpustakaan.borrowings.index' : 
                    ($user->isGuru() ? 'guru.borrowings.index' : 'siswa.borrowings.index');

    return redirect()->route($redirectRoute)
        ->with('success', 'Buku berhasil dipinjam.');
}

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::where('role', '!=', 'perpustakawan')->get();
        
        return view('borrowings.edit', compact('borrowing', 'books', 'users'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrow_date' => 'required|date',
            'due_date' => 'required|date|after:borrow_date',
            'status' => 'required|in:borrowed,returned,overdue',
            'notes' => 'nullable|string',
        ]);

        $borrowing->update($request->all());

        return redirect()->route('perpustakawan.borrowings.index')
            ->with('success', 'Peminjaman berhasil diperbarui.');
    }

    public function destroy(Borrowing $borrowing)
    {
        $borrowing->delete();

        return redirect()->route('perpustakawan.borrowings.index')
            ->with('success', 'Peminjaman berhasil dihapus.');
    }

    public function return(Borrowing $borrowing)
    {
        $borrowing->update([
            'status' => 'returned',
            'return_date' => now(),
        ]);

        $user = auth()->user();
        $redirectRoute = $user->isPerpustakawan() ? 'perpustakawan.borrowings.index' : 
                        ($user->isGuru() ? 'guru.borrowings.index' : 'siswa.borrowings.index');

        return redirect()->route($redirectRoute)
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Update fines for overdue borrowings
     */
    private function updateOverdueFines()
    {
        $overdueBorrowings = Borrowing::where('status', 'borrowed')
            ->where('due_date', '<', now())
            ->get();
        
        foreach ($overdueBorrowings as $borrowing) {
            $borrowing->updateFine();
        }
    }

    /**
     * Mark fine as paid
     */
    public function payFine(Borrowing $borrowing)
    {
        if ($borrowing->fine_amount <= 0 || $borrowing->fine_paid) {
            return back()->with('error', 'Tidak ada denda yang perlu dibayar.');
        }

        $borrowing->markFineAsPaid();

        $user = auth()->user();
        $redirectRoute = $user->isPerpustakawan() ? 'perpustakawan.borrowings.index' : 
                        ($user->isGuru() ? 'guru.borrowings.index' : 'siswa.borrowings.index');

        return redirect()->route($redirectRoute)
            ->with('success', 'Denda berhasil dibayar.');
    }
}