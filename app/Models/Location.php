<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

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
}
