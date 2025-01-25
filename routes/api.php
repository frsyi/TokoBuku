<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\TransactionController;

Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::apiResource('/profile', ProfileController::class);
    Route::apiResource('/book', BookController::class);
    Route::apiResource('/category', CategoryController::class);
    Route::apiResource('/cart', CartController::class);
    Route::apiResource('/transaction', TransactionController::class);
});
