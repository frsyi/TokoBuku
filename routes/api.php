<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\BookController;
use App\Http\Controllers\API\ProfileController;
use App\Http\Controllers\API\CategoryController;

Route::post('/api/register', [AuthController::class, 'register'])->name('api.register');
Route::post('/api/login', [AuthController::class, 'login'])->name('api.login');

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/api/logout', [AuthController::class, 'logout'])->name('api.logout');
    Route::get('/api/profile', [ProfileController::class, 'index'])->name('api.profile');

    Route::apiResource('/api/book', BookController::class)->names([
        'index' => 'api.book.index',
        'store' => 'api.book.store',
        'show' => 'api.book.show',
        'update' => 'api.book.update',
        'destroy' => 'api.book.destroy',
    ]);

    Route::apiResource('/api/category', CategoryController::class)->names([
        'index' => 'api.category.index',
        'store' => 'api.category.store',
        'show' => 'api.category.show',
        'update' => 'api.category.update',
        'destroy' => 'api.category.destroy',
    ]);

});
