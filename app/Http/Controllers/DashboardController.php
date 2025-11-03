<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;

class DashboardController extends Controller
{
    public function perpustakawan()
    {
        // Update fines for overdue borrowings
        $this->updateOverdueFines();
        
        $stats = [
            'total_books' => Book::count(),
            'total_users' => User::count(),
            'total_borrowings' => Borrowing::count(),
            'active_borrowings' => Borrowing::where('status', 'dipinjam')->count(),
            'overdue_borrowings' => Borrowing::where('status', 'terlambat')->count(),
            'total_fines' => Borrowing::where('fine_amount', '>', 0)->sum('fine_amount'),
            'unpaid_fines' => Borrowing::where('fine_amount', '>', 0)->where('fine_paid', false)->sum('fine_amount'),
        ];

        $recent_borrowings = Borrowing::with(['user', 'book'])
            ->latest()
            ->limit(5)
            ->get();

        $recent_books = Book::latest()->limit(5)->get();

        return view('dashboard.perpustakawan', compact('stats', 'recent_borrowings', 'recent_books'));
    }

    public function guru()
    {
        $user = auth()->user();
        
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('stock', '>', 0)->count(),
            'my_borrowings' => Borrowing::where('user_id', $user->id)->count(),
            'active_borrowings' => Borrowing::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count(),
        ];

        $my_borrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $recent_books = Book::where('stock', '>', 0)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.guru', compact('stats', 'my_borrowings', 'recent_books'));
    }

    public function siswa()
    {
        $user = auth()->user();
        
        $stats = [
            'total_books' => Book::count(),
            'available_books' => Book::where('stock', '>', 0)->count(),
            'my_borrowings' => Borrowing::where('user_id', $user->id)->count(),
            'active_borrowings' => Borrowing::where('user_id', $user->id)
                ->where('status', 'dipinjam')
                ->count(),
        ];

        $my_borrowings = Borrowing::with('book')
            ->where('user_id', $user->id)
            ->latest()
            ->limit(5)
            ->get();

        $recent_books = Book::where('stock', '>', 0)
            ->latest()
            ->limit(5)
            ->get();

        return view('dashboard.siswa', compact('stats', 'my_borrowings', 'recent_books'));
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
}
