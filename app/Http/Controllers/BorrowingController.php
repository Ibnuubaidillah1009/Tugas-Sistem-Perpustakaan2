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

        $query = Borrowing::with(['user', 'book']);

        if ($user->isPerpustakawan()) {
            // For perpustakawan, show all borrowings
        } else {
            // For guru and siswa, show only their own borrowings
            $query->where('user_id', $user->id);
        }

        // Handle search query
        if ($search = request('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('book', function ($bookQuery) use ($search) {
                    $bookQuery->where('title', 'like', '%' . $search . '%');
                });
            });
        }

        // Handle status filter
        if ($status = request('status')) {
            $query->where('status', $status);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('borrowings.index', compact('borrowings'));
    }

public function create()
{
    $user = auth()->user();
    
    if ($user->isPerpustakawan()) {
        $books = Book::where('stock', '>', 0)->get();
        $users = User::where('role', '!=', 'perpustakawan')->get();
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
    
    if ($user->isPerpustakawan()) {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
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

    if ($book->available_stock <= 0) {
        return back()->with('error', 'Buku tidak tersedia.');
    }

    // Check if user already has this book borrowed
    $existingBorrowing = Borrowing::where('user_id', $userId)
        ->where('book_id', $book->id)
        ->where('status', 'dipinjam')
        ->first();

    if ($existingBorrowing) {
        return back()->with('error', 'Anda sudah meminjam buku ini.');
    }

    Borrowing::create([
        'user_id' => $userId,
        'book_id' => $book->id,
        'borrow_date' => now(),
        'due_date' => $request->due_date ?? now()->addDays(7),
        'status' => 'menunggu_perizinan',
        'approval_status' => 'menunggu_perizinan',
        'notes' => $request->notes,
    ]);

    $redirectRoute = $user->isPerpustakawan() ? 'perpustakawan.borrowings.index' :
                    ($user->isGuru() ? 'guru.borrowings.index' : 'siswa.borrowings.index');

    return redirect()->route($redirectRoute)
        ->with('success', 'Permintaan peminjaman berhasil diajukan dan menunggu persetujuan admin.');
}

    public function show(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book']);
        return view('borrowings.show', compact('borrowing'));
    }

    public function printReceipt(Borrowing $borrowing)
    {
        $borrowing->load(['user', 'book']);
        $librarian = auth()->user();
        return view('borrowings.receipt', compact('borrowing', 'librarian'));
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
            'status' => 'required|in:menunggu_perizinan,dipinjam,dikembalikan,terlambat',
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
        // Only librarians can return books
        if (!auth()->user()->isPerpustakawan()) {
            abort(403, 'Akses ditolak. Hanya perpustakawan yang dapat mengembalikan buku.');
        }

        $borrowing->update([
            'status' => 'dikembalikan',
            'return_date' => now(),
        ]);

        return redirect()->route('perpustakawan.borrowings.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    /**
     * Update fines for overdue borrowings
     */
    private function updateOverdueFines()
    {
        // Set status to 'terlambat' for borrowings overdue by more than 7 days
        $severelyOverdueBorrowings = Borrowing::where('status', 'dipinjam')
            ->where('due_date', '<', now()->subDays(7))
            ->whereHas('user', function($q) {
                $q->where('role', 'siswa');
            })
            ->get();

        foreach ($severelyOverdueBorrowings as $borrowing) {
            $borrowing->update(['status' => 'terlambat']);
            $borrowing->updateFine();
        }

        // Update fines for all overdue borrowings (in case some are between 0-7 days overdue)
        $allOverdueBorrowings = Borrowing::where('status', 'dipinjam')
            ->where('due_date', '<', now())
            ->whereHas('user', function($q) {
                $q->where('role', 'siswa');
            })
            ->get();

        foreach ($allOverdueBorrowings as $borrowing) {
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

    /**
     * Show pending approvals for admin
     */
    public function permissions()
    {
        $borrowings = Borrowing::with(['user', 'book'])
            ->where('approval_status', 'menunggu_perizinan')
            ->latest()
            ->paginate(10);

        return view('borrowings.permissions', compact('borrowings'));
    }

    /**
     * Approve a borrowing request
     */
    public function approve(Borrowing $borrowing)
    {
        if (!auth()->user()->isPerpustakawan()) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->update([
            'approval_status' => 'disetujui',
            'status' => 'dipinjam'
        ]);

        return redirect()->route('perpustakawan.borrowings.permissions')
            ->with('success', 'Peminjaman berhasil disetujui.');
    }

    /**
     * Reject a borrowing request
     */
    public function reject(Borrowing $borrowing)
    {
        if (!auth()->user()->isPerpustakawan()) {
            abort(403, 'Akses ditolak.');
        }

        $borrowing->update([
            'approval_status' => 'ditolak'
        ]);

        return redirect()->route('perpustakawan.borrowings.permissions')
            ->with('success', 'Peminjaman berhasil ditolak.');
    }
}
