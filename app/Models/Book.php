<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

protected $fillable = [
    'title', 'author', 'isbn', 'description', 'stock', 'category', 
    'publication_year', 'publisher', 'photo', 'cover_image',
];

public function getPhotoUrlAttribute()
{
    if ($this->photo) {
        return asset('storage/photos/books/' . $this->photo);
    }
    return asset('images/default-book.png');
}

public function getCoverImageUrlAttribute()
{
    if ($this->cover_image) {
        return asset('storage/photos/books/covers/' . $this->cover_image);
    }
    return asset('images/default-cover.png');
}
    /**
     * Get the borrowings for the book.
     */
    public function borrowings()
    {
        return $this->hasMany(Borrowing::class);
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
