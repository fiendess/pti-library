<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
      protected $fillable = ['name'];
      

      public function genre()
{
    return $this->belongsTo(Genre::class);
}

}
