<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    //
    public function media()
    {
        return $this->belongsToMany(Media::class);
    }
}
