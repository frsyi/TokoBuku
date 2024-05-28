<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'dashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('book', BookController::class);
    Route::resource('category', CategoryController::class);
    Route::resource('catalogue', CatalogueController::class);

    Route::get('/book/{book}', [BookController::class, 'show'])->name('book.detail');
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue.index');
});

Route::middleware(['admin'])->group(function () {
});

require __DIR__ . '/auth.php';
