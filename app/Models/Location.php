<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    public function usersWhoFavorited()
{
    return $this->belongsToMany(User::class, 'user_favorite_libraries');
}


    protected $fillable = [
        'name', 
        'address',
        'latitude', 
        'longitude', 
        'contact_number', 
        'opening_hours',
        'website', 
        'type', 
    ];

     protected $casts = [
        'contact_number' => 'array', // Otomatis diubah ke JSON
        'opening_hours' => 'array',  // Otomatis diubah ke JSON
    ];
}
