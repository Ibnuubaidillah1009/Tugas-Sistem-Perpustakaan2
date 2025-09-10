<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\UserController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Perpustakawan routes
Route::middleware(['auth', 'role:perpustakawan'])->prefix('perpustakawan')->name('perpustakawan.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'perpustakawan'])->name('dashboard');
    Route::resource('books', BookController::class);
    Route::resource('borrowings', BorrowingController::class);
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::patch('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay-fine');
    Route::resource('users', UserController::class);
});

// Guru routes
Route::middleware(['auth', 'role:guru'])->prefix('guru')->name('guru.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'guru'])->name('dashboard');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::patch('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay-fine');
});

// Siswa routes
Route::middleware(['auth', 'role:siswa'])->prefix('siswa')->name('siswa.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'siswa'])->name('dashboard');
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
    Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
    Route::patch('/borrowings/{borrowing}/return', [BorrowingController::class, 'return'])->name('borrowings.return');
    Route::patch('/borrowings/{borrowing}/pay-fine', [BorrowingController::class, 'payFine'])->name('borrowings.pay-fine');
});