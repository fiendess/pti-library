<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\BooksController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;


// register
Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);

// login
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout']);

// profile
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UserController::class, 'index'])->name('profile.index');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::get('/favorites/libraries', [UserController::class, 'favoritelibraries'])->name('favorites.libraries');
    Route::get('/favorites/books', [UserController::class, 'favoritebooks'])->name('favorites.books');
});

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

Route::middleware('auth')->group(function () {
    Route::post('/wishlist/add', [WishlistController::class, 'addToWishlist']);
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.get');
    Route::delete('/wishlist/remove/{book_id}', [WishlistController::class, 'removeFromWishlist'])->name('wishlist.remove');
});

Route::get('/books-detaildb/{id}', [BooksController::class, 'showdb']);
Route::get('/search-books', [BooksController::class, 'searchBooks'])->name('search.books');
Route::get('/find-libraries', [LocationsController::class, 'findLibraries'])->name('find.libraries');   
Route::get('/search-locations', [LocationsController::class, 'searchLocations'])->name('search.locations');
Route::post('/add-to-favorites', [LocationsController::class, 'addToFavorites']);
