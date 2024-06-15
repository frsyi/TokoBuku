<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;

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
    Route::resource('order', OrderController::class);

    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('order/{id}', [OrderController::class, 'show'])->name('order.show');
    Route::get('order/detail/{id}', [OrderController::class, 'detail'])->name('order.detail');
    Route::post('/order/store/{id}', [OrderController::class, 'store'])->name('order.store');
    Route::delete('/order/{id}', [OrderController::class, 'destroy'])->name('order.destroy');
    Route::patch('/order/{order}/complete', [OrderController::class, 'complete'])->name('order.complete');
    Route::patch('/order/{order}/incomplete', [OrderController::class, 'uncomplete'])->name('order.uncomplete');

    Route::post('/payment', [OrderController::class, 'payment'])->name('payment');

    Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/transactions/history', [TransactionController::class, 'history'])->name('transactions.history');
    Route::post('/transactions/{id}/tracking', [TransactionController::class, 'updateTrackingNumber'])->name('transactions.updateTrackingNumber');
});


Route::middleware(['admin'])->group(function () {
});

require __DIR__ . '/auth.php';
