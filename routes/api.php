<?php

use App\Http\Controllers\UserAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;

Route::apiResource('books', BookController::class);


Route::get('/', function () {
    return response()->json(['message' => 'Book API is running!'], 200);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // ✅ User resource routes
    Route::apiResource('users', UserController::class);
});

Route::post('/sign-in', [UserAuthController::class, 'signIn'])->name('login');
Route::post('/sign-up', [UserAuthController::class, 'signUp']);