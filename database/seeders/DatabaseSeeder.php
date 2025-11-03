<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create perpustakawan
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'perpustakawan',
        ]);

        // Create guru
        User::create([
            'name' => 'Bu Sadiyah',
            'email' => 'BuSadiyah@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Pak Wildam',
            'email' => 'PakWildam@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        User::create([
            'name' => 'Pak Shobi',
            'email' => 'PakShobi@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // Create siswa
        User::create([
            'name' => 'Novta',
            'email' => 'Novta@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        User::create([
            'name' => 'Fadhil',
            'email' => 'Fadhil@gmail.com',
            'password' => Hash::make('Fadhil123'),
            'role' => 'siswa',
        ]);

        User::create([
            'name' => 'Dhiya',
            'email' => 'Dhiya@gmail.com',
            'password' => Hash::make('password'),
            'role' => 'siswa',
        ]);

        // Create sample books
        $books = [
            [
                'title' => 'Rumus Matematika Dasar',
                'author' => 'Dr. Ahmad Susanto',
                'isbn' => '978-602-123456-7',
                'description' => 'Buku matematika dasar untuk siswa SMK/SMA',
                'stock' => 5,
                'category' => 'Pendidikan',
                'publication_year' => 2021,
                'publisher' => 'SMK NEGERI 1 BANGIL',
            ],
            [
                'title' => 'Fisika',
                'author' => 'Prof. Dr. Budi Santoso',
                'isbn' => '978-602-123457-8',
                'description' => 'Pengantar fisika modern untuk mahasiswa',
                'stock' => 3,
                'category' => 'Pendidikan',
                'publication_year' => 2008,
                'publisher' => 'Gramedia',
            ],
            [
                'title' => 'Sejarah Indonesia',
                'author' => 'Dr. Siti Aminah',
                'isbn' => '978-602-123458-9',
                'description' => 'Sejarah lengkap Indonesia dari masa pra-kolonial hingga modern',
                'stock' => 4,
                'category' => 'Sejarah',
                'publication_year' => 2021,
                'publisher' => 'Perpustakaan Nesaba',
            ],
            [
                'title' => 'Pemrograman Web',
                'author' => 'Dhany',
                'isbn' => '978-602-123459-0',
                'description' => 'Panduan lengkap pemrograman web dengan PHP dan Laravel',
                'stock' => 2,
                'category' => 'Teknologi',
                'publication_year' => 2023,
                'publisher' => 'Gramedia',
            ],
            [
                'title' => 'Novel Laskar Pelangi',
                'author' => 'Andrea Hirata',
                'isbn' => '978-602-123460-1',
                'description' => 'Novel inspiratif tentang perjuangan anak-anak di Belitung',
                'stock' => 6,
                'category' => 'Fiksi',
                'publication_year' => 2005,
                'publisher' => 'Bentang Pustaka',
            ],
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }

        // Create sample borrowings
        $guru = User::where('role', 'guru')->first();
        $siswa = User::where('role', 'siswa')->first();
        $book1 = Book::first();
        $book2 = Book::skip(1)->first();

        Borrowing::create([
            'user_id' => $guru->id,
            'book_id' => $book1->id,
            'borrow_date' => now()->subDays(5),
            'due_date' => now()->addDays(2),
            'status' => 'dipinjam',
            'notes' => 'Untuk persiapan mengajar',
        ]);

        Borrowing::create([
            'user_id' => $siswa->id,
            'book_id' => $book2->id,
            'borrow_date' => now()->subDays(3),
            'due_date' => now()->addDays(4),
            'status' => 'dipinjam',
            'notes' => 'Untuk tugas sekolah',
        ]);

        // Create sample overdue borrowing with fine for siswa
        Borrowing::create([
            'user_id' => $siswa->id,
            'book_id' => $book2->id,
            'borrow_date' => now()->subDays(15),
            'due_date' => now()->subDays(8),
            'status' => 'terlambat',
            'notes' => 'Buku terlambat',
            'fine_amount' => 2000,
            'fine_paid' => false,
            'fine_calculated_at' => now()->subDays(1),
        ]);

        // Contoh 1: Peminjaman siswa terlambat 7 hari, denda Rp 2.000
        Borrowing::create([
            'user_id' => $siswa->id,
            'book_id' => $book1->id,
            'borrow_date' => now()->subDays(20),
            'due_date' => now()->subDays(13),
            'status' => 'terlambat',
            'notes' => 'Buku terlambat dikembalikan',
            'fine_amount' => 2000,
            'fine_paid' => false,
            'fine_calculated_at' => now()->subDays(2),
        ]);

        // Contoh 2: Peminjaman siswa terlambat 14 hari, denda Rp 4.000
        Borrowing::create([
            'user_id' => $siswa->id,
            'book_id' => Book::skip(2)->first()->id,
            'borrow_date' => now()->subDays(25),
            'due_date' => now()->subDays(18),
            'status' => 'terlambat',
            'notes' => 'Buku sangat terlambat',
            'fine_amount' => 4000,
            'fine_paid' => false,
            'fine_calculated_at' => now()->subDays(3),
        ]);
    }
}
