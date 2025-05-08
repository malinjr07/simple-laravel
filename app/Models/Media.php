<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    protected $fillable = [
        'url',
        'name',
        'extension',
        'mime_type',
        'disk',
        'path',
        'size',
    ];

}
