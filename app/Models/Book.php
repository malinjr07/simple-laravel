<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    //
    use HasFactory;
    protected $primaryKey = 'book_id';

    public const CREATED_AT = 'book_created_at';
    public const UPDATED_AT = 'book_updated_at';

    protected $fillable = [
        'book_title',
        'book_author',
        'book_publication_year',
    ];
}
