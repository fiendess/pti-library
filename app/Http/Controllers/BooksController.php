<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;


class BooksController extends Controller
{
        
  public function index()
    {
        // Cek apakah data buku sudah ada di database
        $books = Book::all();

        // Jika tidak ada buku, ambil dari API dan simpan ke database
        if ($books->isEmpty()) {
            // URL API untuk mengambil data buku dari Google Books
            $apiUrl = 'https://www.googleapis.com/books/v1/volumes';
            
            // Fetch data dari API dengan query pencarian (misalnya 'fiction') dan parameter lainnya
            $response = Http::get($apiUrl, [
                'q' => 'fiction', 
                'key' => config('services.google_books.key'),
                'maxResults' => 40, 
            ]);

            // Validasi jika respons sukses dan ada data buku
            if ($response->successful() && isset($response->json()['items'])) {
                // Ambil 5 buku secara acak
                $booksData = collect($response->json()['items'])->random(5)->map(function ($book) {
                    return [
                        'title' => $book['volumeInfo']['title'] ?? 'No Title',
                        'author' => implode(', ', $book['volumeInfo']['authors'] ?? ['Unknown Author']),
                        'description' => $book['volumeInfo']['description'] ?? 'No description available.',
                        'publisher' => $book['volumeInfo']['publisher'] ?? 'Unknown Publisher',
                        'published_date' => isset($book['volumeInfo']['publishedDate']) ? $book['volumeInfo']['publishedDate'] : null,
                        'isbn' => isset($book['volumeInfo']['industryIdentifiers'][0]['identifier']) ? $book['volumeInfo']['industryIdentifiers'][0]['identifier'] : 'N/A',
                        'cover_image' => isset($book['volumeInfo']['imageLinks']['thumbnail']) 
                            ? $book['volumeInfo']['imageLinks']['thumbnail'] 
                            : asset('images/no-cover.png'),
                        'category' => $book['volumeInfo']['categories'][0] ?? 'General',
                        'quantity' => 1, 
                        'availability' => 'available', 
                        'rating' => isset($book['volumeInfo']['averageRating']) 
                            ? $book['volumeInfo']['averageRating'] 
                            : null, 
                    ];
                });

                // Simpan data buku ke database
                foreach ($booksData as $bookData) {
                    Book::create($bookData);
                }

                // Ambil data buku yang baru disimpan di database
                $books = Book::all();
            } else {
                // Jika tidak berhasil, gunakan fallback data kosong
                $books = collect([]);
            }
        }
        return view('home', [
            'title' => 'Home Page',
            'books' => $books,
        ]);
    }


    public function show($id)
{
    // Ambil data dari Google Books API
    $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$id}");

    if ($response->successful()) {
        $data = $response->json();

        // Format data agar sesuai dengan yang digunakan di view
        $book = (object) [
            'title' => $data['volumeInfo']['title'] ?? 'Unknown Title',
            'author' => isset($data['volumeInfo']['authors']) 
                        ? implode(', ', $data['volumeInfo']['authors']) 
                        : 'Unknown Author',
            'cover_image' => $data['volumeInfo']['imageLinks']['thumbnail'] ?? '',
            'description' => $data['volumeInfo']['description'] ?? 'No description available.',
            'publisher' => $data['volumeInfo']['publisher'] ?? 'Unknown Publisher',
            'published_date' => $data['volumeInfo']['publishedDate'] ?? 'Unknown Date',
            'isbn' => isset($data['volumeInfo']['industryIdentifiers']) 
                      ? implode(', ', array_map(function ($isbn) {
                            return "{$isbn['type']}: {$isbn['identifier']}";
                        }, $data['volumeInfo']['industryIdentifiers']))
                      : 'No ISBN available',
        ];


        return view('books-detail', compact('book'));
    } else {      
        abort(404, 'Book not found');
    }
}

    public function searchBooks(Request $request)
    {
        // Validasi input query
        $request->validate([
            'query' => 'required|string',
        ]);

        // Ambil query pencarian
        $query = $request->input('query');
        $apiKey = config('services.google_books.key'); 

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

