<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;


Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/dashboard', function () {
     return view('dashboard.dashboard', ['title' => 'Dashboard']);
});

// Route::get('/dashboard/books', [BooksController::class, 'index'])->name('dashboard.books.index');
Route::get('/dashboard/books', [BooksController::class, 'index']);

Route::get('/mybook', function () {
    return view('mybook', ['title' => 'My Book']);
});

Route::get('/browse', function () {
    return view('browse', ['title' => 'Browse']);
});

Route::get('/recommendation', function () {
    return view('recommendation', ['title' => 'Recommendation']);
});

// routes/web.php
// Route::get('/dashboard/books/search', [BookController::class, 'search'])->name('books.search');
// Route::get('/dashboard/books/{id}', [BookController::class, 'show'])->name('books.show');
// Route::post('/dashboard/books', [BookController::class, 'store'])->name('books.store');
Route::get('/dashboard/books/search', [BooksController::class, 'search'])->name('books.search');
Route::get('/dashboard/books/create/{googleBooksId}', [BooksController::class, 'create'])->name('books.create');
Route::post('/dashboard/books', [BooksController::class, 'store'])->name('books.store');