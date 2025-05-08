<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;

Route::apiResource('/', BookController::class);

