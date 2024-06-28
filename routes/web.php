<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

Route::get('/', WelcomeController::class)->name('welcome');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('book', BookController::class)->names([
        'index' => 'book.index',
        'create' => 'book.create',
        'store' => 'book.store',
        'show' => 'book.show',
        'edit' => 'book.edit',
        'update' => 'book.update',
        'destroy' => 'book.destroy',
    ]);

    Route::resource('category', CategoryController::class)->names([
        'index' => 'category.index',
        'create' => 'category.create',
        'store' => 'category.store',
        'show' => 'category.show',
        'edit' => 'category.edit',
        'update' => 'category.update',
        'destroy' => 'category.destroy',
    ]);

    Route::resource('cart', CartController::class)->except(['show'])->names([
        'index' => 'cart.index',
        'create' => 'cart.create',
        'store' => 'cart.store',
        'edit' => 'cart.edit',
        'update' => 'cart.update',
        'destroy' => 'cart.destroy',
    ]);
    Route::get('cart/show/{id}', [CartController::class, 'show'])->name('cart.show');

    Route::get('transaction', [TransactionController::class, 'index'])->name('transaction.index');
    Route::get('transaction/create', [TransactionController::class, 'create'])->name('transaction.create');
    Route::get('transaction/show/{id}', [TransactionController::class, 'show'])->name('transaction.show');
    Route::post('transaction/checkout', [TransactionController::class, 'checkout'])->name('transaction.checkout');
    Route::patch('transaction/{id}/updateTrackingNumber', [TransactionController::class, 'updateTrackingNumber'])->name('transaction.updateTrackingNumber');
    Route::patch('transaction/{transaction}/complete', [TransactionController::class, 'complete'])->name('transaction.complete');
    Route::patch('transaction/{transaction}/uncomplete', [TransactionController::class, 'uncomplete'])->name('transaction.uncomplete');
});

// Route group for admin middleware
Route::middleware(['admin'])->group(function () {
    // Define admin routes here if needed
});

require __DIR__ . '/auth.php';
