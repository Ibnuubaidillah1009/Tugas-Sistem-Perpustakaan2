<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Storage;

class Book extends Model
{
    use HasFactory;

protected $fillable = [
    'title', 'author', 'isbn', 'barcode', 'description', 'stock', 'category',
    'publication_year', 'publisher', 'photo', 'cover_image',
];

public function getPhotoUrlAttribute()
{
    if ($this->photo && file_exists(public_path('images/' . $this->photo))) {
        return asset('images/' . $this->photo);
    }
    return asset('images/default-book.png');
}


    /**
     * Get the borrowings for the book.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // Prevent deletion if book has active borrowings
        static::deleting(function ($book) {
            if ($book->borrowings()->where('status', 'dipinjam')->exists()) {
                // Don't delete the book, but allow force delete
                if (!request()->has('force_delete')) {
                    return false;
                }
            }
        });
    }

    /**
     * Get available stock
     */
    public function getAvailableStockAttribute()
    {
        $borrowed = $this->borrowings()->where('status', 'borrowed')->count();
        return $this->stock - $borrowed;
    }
}
