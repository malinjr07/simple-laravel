<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::apiResource('books', BookController::class);

Route::get('/', function () {
    return response()->json(['message' => 'Book API is running!'], 200);
});
