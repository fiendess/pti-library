<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('home', ['title' => 'Home Page']);
});

Route::get('/dashboard', function () {
    return view('dashboard');
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