<?php

use App\Http\Controllers\MediaController;
use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [UserAuthController::class, 'logout']);
    Route::apiResource('users', UserController::class);
});

Route::get('/', function () {
    return response()->json(['message' => 'Book API is running!'], 200);
});


Route::apiResource('books', BookController::class);



Route::post('/sign-in', [UserAuthController::class, 'signIn'])->name('login');
Route::post('/sign-up', [UserAuthController::class, 'signUp']);

Route::post("/upload-image", [MediaController::class, "uploadImage"]);
Route::post("/upload-video", [MediaController::class, "uploadVideo"]);