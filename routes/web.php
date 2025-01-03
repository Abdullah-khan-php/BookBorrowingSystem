


<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BookController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('books/fetch', [BookController::class, 'fetchBooks'])->name('books.fetch');
Route::get('books/export-pdf', [BookController::class, 'exportPDF'])->middleware('auth')->name('books.exportPDF');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Admin-specific routes
    Route::middleware('role:admin')->group(function () {
        Route::get('books', [BookController::class, 'index'])->name('books.index'); // Listing
        Route::get('books/create', [BookController::class, 'create'])->name('books.create'); // Create Form
        Route::post('books', [BookController::class, 'store'])->name('books.store'); // Store
        Route::get('books/{id}', [BookController::class, 'edit'])->name('books.edit'); // Edit Form
        Route::put('books/{id}', [BookController::class, 'update'])->name('books.update'); // Update
        Route::delete('books/{id}', [BookController::class, 'destroy'])->name('books.destroy'); // Delete
    });

    // User-specific routes
    Route::middleware('role:user')->group(function () {
        Route::post('/books/{id}/return', [BookController::class, 'returnBook'])->name('books.return');
        Route::get('books', [BookController::class, 'index'])->name('books.index'); // Listing
        Route::post('books/{id}/borrow', [BookController::class, 'borrow'])->name('books.borrow'); // Borrow
    });

    // Shared routes (both admin and user)
    Route::get('books', [BookController::class, 'index'])->name('books.index'); // Listing for all
});

require __DIR__.'/auth.php';