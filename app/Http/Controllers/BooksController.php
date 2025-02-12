<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Book;


class BooksController extends Controller
{
        

  public function index()
    {

        $books = Book::all();
        if ($books->isEmpty()) {
            $apiUrl = 'https://www.googleapis.com/books/v1/volumes';

            $response = Http::get($apiUrl, [
                'q' => 'fiction', 
                'key' => config('services.google_books.key'),
                'maxResults' => 40, 
            ]);

            if ($response->successful() && isset($response->json()['items'])) {
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

                foreach ($booksData as $bookData) {
                    Book::create($bookData);
                }
                $books = Book::all();
            } else {
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

        $response = Http::get("https://www.googleapis.com/books/v1/volumes/{$id}");

        if ($response->successful()) {
            $data = $response->json();

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
        
        $request->validate([
            'query' => 'required|string',
        ]);

      
        $query = $request->input('query');
        $apiKey = config('services.google_books.key'); 


        $response = Http::get('https://www.googleapis.com/books/v1/volumes', [
            'q' => $query,
            'key' => $apiKey,
            'maxResults' => 10, 
        ]);

        if ($response->successful()) {
            $booksData = $response->json();

            return view('books.index', ['books' => $booksData['items'] ?? []]);
        }

        return back()->with('error', 'Failed to fetch books from Google Books API.');
    }

    public function showdb($id)
    {
    
        $book = Book::findOrFail($id); 
        return view('books-detaildb', compact('book'));
    }
}

