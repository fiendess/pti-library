<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleBooksService
{
    protected $apiKey;
    protected $baseUrl = 'https://www.googleapis.com/books/v1/volumes';

    public function __construct()
    {
        $this->apiKey = config('services.google.books.key');
    }

    // Pencarian umum
    public function searchBooks($query)
    {
        $response = Http::get($this->baseUrl, [
            'q' => $query,
            'key' => $this->apiKey
        ]);

        return $response->json();
    }

    // Pencarian spesifik by ISBN
    public function searchByIsbn($isbn)
    {
        return $this->searchBooks("isbn:$isbn");
    }

    // Detail buku by ID
    public function getBooksById($id)
    {
        $response = Http::get("{$this->baseUrl}/{$id}", [
            'key' => $this->apiKey
        ]);

        return $response->json();
    }
}

