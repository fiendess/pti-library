<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BooksFactory> */
    use HasFactory;

    protected $table = 'books';

    protected $fillable = [
        'title',
        'author',
        'description',
        'publisher',
        'publish_date',
        'isbn',
        'cover_image',
        'quantity',
        'availability',

    ];
    
    protected $attributes = [
    'genre_id' => 1, 
];

    protected $casts = [
        'availability' => 'boolean', 
    ];
}
