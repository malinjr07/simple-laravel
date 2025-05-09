<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    //
    public function products()
    {
        return $this->belongsToMany(Product::class);
    }

    protected $primaryKey = 'media_id';

    public const CREATED_AT = 'media_created_at';
    public const UPDATED_AT = 'media_updated_at';
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
