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
        return view('dashboard.books.index', compact('books'));
    }

    
    public function addFromAPI(Request $request)
    {
        $request->validate([
            'query' => 'required|string',
        ]);

        $query = $request->input('query');
        $apiKey = env('GOOGLE_BOOKS_API_KEY');

        
        $response = Http::get("https://www.googleapis.com/books/v1/volumes", [
            'q' => $query,
            'key' => $apiKey,
            'maxResults' => 5, 
        ]);

        if ($response->successful()) {
            $booksData = $response->json();

            if (isset($booksData['items'])) {
                foreach ($booksData['items'] as $item) {
                    $volumeInfo = $item['volumeInfo'];

                    
                    Book::create([
                        'title' => $volumeInfo['title'] ?? 'Unknown Title',
                        'author' => implode(', ', $volumeInfo['authors'] ?? ['Unknown Author']),
                        'description' => $volumeInfo['description'] ?? null,
                        'publisher' => $volumeInfo['publisher'] ?? null,
                        'published_date' => $volumeInfo['publishedDate'] ?? null,
                        'isbn' => $this->getISBN($volumeInfo['industryIdentifiers'] ?? []),
                        'cover_image' => $volumeInfo['imageLinks']['thumbnail'] ?? null,
                        'quantity' => 1, 
                        'availability' => 1, 
                        'view_count' => 0, 
                        'user_id' => auth()->id(),
                        'genre_id' => $this->getGenreId($volumeInfo['categories'] ?? []),
                    ]);
                }
            }
        }

        return redirect()->route('dashboard.books.index')->with('success', 'Books added successfully from API!');
    }

    // Helper function to extract ISBN
    private function getISBN($identifiers)
    {
        foreach ($identifiers as $id) {
            if ($id['type'] === 'ISBN_13') {
                return $id['identifier'];
            }
        }
        return null;
    }


    private function getGenreId($categories)
    {
 
        return 1; 
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string',
        'author' => 'required|string',
        'isbn' => 'required|string|unique:books,isbn',
        'publisher' => 'required|string',
        'published_date' => 'required|date',
        'quantity' => 'required|integer',
        'description' => 'nullable|string',
        'genre_id' => 'required|integer',
    ]);

    Book::create($request->all());

    return redirect()->back()->with('success', 'Book added successfully!');
}

}
