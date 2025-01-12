<?php

namespace App\Http\Controllers;

use App\Models\Books;
use Illuminate\Http\Request;
use App\Services\GoogleBooksService;


class BooksController extends Controller
{
    public function index()
    {
        return view('dashboard.books', [
            'title' => 'Books',
            'books' => Books::all()
        ]);
}
 protected $googleBooks;

    public function __construct(GoogleBooksService $googleBooks)
    {
        $this->googleBooks = $googleBooks;
    }

    // Halaman pencarian buku
    public function search(Request $request)
    {
        $searchResults = [];
        
        if ($request->has('q')) {
            $response = $this->googleBooks->searchBooks($request->q);
            $searchResults = $response['items'] ?? [];
        }

        return view('books.search', compact('searchResults'));
    }

    // Menampilkan form tambah buku dengan data dari API
    public function create($googleBooksId)
    {
        $booksData = $this->googleBooks->getBooksById($googleBooksId);
        return view('dashboard.books.create', compact('booksData'));
    }

    // Menyimpan buku ke database
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'author' => 'required',
            'quantity' => 'required|numeric|min:0'
        ]);

        try {
            Books::create([
                'title' => $request->title,
                'author' => $request->author,
                'description' => $request->description,
                'publisher' => $request->publisher,
                'published_date' => $request->published_date,
                'isbn' => $request->isbn,
                'cover_image' => $request->cover_image,
                'category' => $request->category,
                'quantity' => $request->quantity,
                'availability' => $request->quantity > 0 ? 1 : 0
                
            ]);

            return redirect()->route('books.index')
                ->with('success', 'Buku berhasil ditambahkan!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menambahkan buku!');
        }
    }
}