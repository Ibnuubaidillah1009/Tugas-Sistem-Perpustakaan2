<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'borrow_date',
        'return_date',
        'due_date',
        'status',
        'approval_status',
        'notes',
        'fine_amount',
        'fine_paid',
        'fine_calculated_at',
    ];

    protected $casts = [
        'borrow_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
        'fine_amount' => 'decimal:2',
        'fine_paid' => 'boolean',
        'fine_calculated_at' => 'datetime',
    ];

    /**
     * Get the user that owns the borrowing.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the book that was borrowed.
     */
    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Check if borrowing is overdue
     */
    public function isOverdue()
    {
        return $this->status === 'dipinjam' && $this->due_date < now();
    }

    /**
     * Calculate fine amount based on overdue days
     */
    public function calculateFine()
    {
        // Only apply fines to siswa (students)
        if (!$this->user->isSiswa()) {
            return 0;
        }

        if ($this->status !== 'dipinjam' || $this->due_date >= now()) {
            return 0;
        }

        $overdueDays = now()->diffInDays($this->due_date);

        if ($overdueDays <= 0) {
            return 0;
        }

        // Denda dimulai setelah 7 hari terlambat
        if ($overdueDays <= 7) {
            return 0;
        }

        // Setiap 7 hari tambahan, denda bertambah 2000
        $finePeriods = floor(($overdueDays - 7) / 7);
        return $finePeriods * 2000;
    }

    /**
     * Update fine amount and mark as calculated
     */
    public function updateFine()
    {
        $fineAmount = $this->calculateFine();
        
        if ($fineAmount > 0) {
            $this->update([
                'fine_amount' => $fineAmount,
                'fine_calculated_at' => now(),
                'status' => 'terlambat'
            ]);
        }
        
        return $fineAmount;
    }

    /**
     * Mark fine as paid
     */
    public function markFineAsPaid()
    {
        $this->update([
            'fine_paid' => true
        ]);
    }

    /**
     * Get status text in Indonesian
     */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'menunggu_perizinan' => 'Menunggu Perizinan',
            'dipinjam' => 'Sedang Dipinjam',
            'dikembalikan' => 'Sudah Dikembalikan',
            'terlambat' => 'Terlambat',
            default => ucfirst($this->status)
        };
    }

    /**
     * Get approval status text in Indonesian
     */
    public function getApprovalStatusTextAttribute()
    {
        return match($this->approval_status) {
            'menunggu_perizinan' => 'Menunggu Perizinan',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => ucfirst($this->approval_status)
        };
    }
}
