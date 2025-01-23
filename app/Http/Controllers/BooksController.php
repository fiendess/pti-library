<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;


class BooksController extends Controller
{
    
    public function searchBooks(Request $request)
    {
        // Validasi input query
        $request->validate([
            'query' => 'required|string',
        ]);

        // Ambil query pencarian
        $query = $request->input('query');
        $apiKey = env('GOOGLE_BOOKS_API_KEY'); // Key untuk Google Books API

        // Panggil API Google Books
        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'key' => $apiKey,
            'maxResults' => 10, // Batasan jumlah hasil yang ingin ditampilkan
        ]);

        if ($response->successful()) {
            $booksData = $response->json();

            // Kembalikan hasil pencarian buku ke tampilan
            return view('books.index', ['books' => $booksData['items'] ?? []]);
        }

        // Jika gagal, tampilkan pesan error
        return back()->with('error', 'Failed to fetch books from Google Books API.');
    }
}

