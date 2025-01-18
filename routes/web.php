<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;;

// Route frontend
Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/mybook', function () {
    return view('mybook', ['title' => 'My Book']);
});

Route::get('/browse', function () {
    return view('browse', ['title' => 'Browse']);
});

Route::get('/recommendation', function () {
    return view('recommendation', ['title' => 'Recommendation']);
});


// Route dashboard admin

Route::get('/dashboard', function () {
    return view('dashboard.dashboard', ['title' => 'Dashboard']);
})->name('dashboard');

// Buku
Route::get('/dashboard/books', [BooksController::class, 'index'])->name('books.index');
Route::post('/dashboard/books/add-from-api', [BooksController::class, 'addFromAPI'])->name('books.addFromAPI');
Route::post('/dashboard/books/store', [BooksController::class, 'store'])->name('books.store');
