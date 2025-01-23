<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\LocationsController;

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

Route::get('/search-books', [BooksController::class, 'searchBooks'])->name('search.books');
Route::get('/search-locations', [LocationsController::class, 'searchLocations'])->name('search.locations');
Route::get('/find-libraries', [LocationController::class, 'findLibraries'])->name('find.libraries');   
// Route untuk mencari lokasi berdasarkan koordinat pengguna
Route::get('/search-locations-nearby', [LocationsController::class, 'searchNearbyLocations']);

