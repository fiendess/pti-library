<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\LocationsController;



Route::get('/', [BooksController::class, 'index'])->name('home');
Route::get('/books-detail/{id}', [BooksController::class, 'show'])->name('books.show');


Route::get('/libraries', function () {
    return view('libraries', ['title' => 'Libraries']);
});

Route::get('/browse', function () {
    return view('browse', ['title' => 'Browse']);
});

Route::get('/about', function () {
    return view('about', ['title' => 'About']);
});

Route::get('/books-detail', function () {
    return view('books-detail', ['title' => 'Books Detail']);
});

Route::get('/search-books', [BooksController::class, 'searchBooks'])->name('search.books');
Route::get('/search-locations', [LocationsController::class, 'searchLocations'])->name('search.locations');
Route::get('/find-libraries', [LocationController::class, 'findLibraries'])->name('find.libraries');   
// Route untuk mencari lokasi berdasarkan koordinat pengguna
Route::get('/search-locations-nearby', [LocationsController::class, 'searchNearbyLocations']);

