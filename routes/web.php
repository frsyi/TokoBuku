<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BookController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CatalogueController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PaymentController;
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

    Route::get('/payment', [PaymentController::class, 'index'])->name('payment.index');
    Route::get('payment/{id}', [PaymentController::class, 'show'])->name('payment.show');
    Route::get('payment/detail/{id}', [PaymentController::class, 'detail'])->name('payment.detail');
    Route::post('/payment/store/{id}', [PaymentController::class, 'store'])->name('payment.store');
    Route::delete('/payment/{id}', [PaymentController::class, 'destroy'])->name('payment.destroy');
    Route::post('/payment/{id}/tracking', [PaymentController::class, 'updateTrackingNumber'])->name('payment.updateTrackingNumber');
    Route::patch('/payment/{order}/complete', [PaymentController::class, 'complete'])->name('payment.complete');
    Route::patch('/payment/{order}/incomplete', [PaymentController::class, 'uncomplete'])->name('payment.uncomplete');


    Route::get('/order', [OrderController::class, 'index'])->name('order.index');
    Route::get('/order/history', [OrderController::class, 'history'])->name('order.history');
});


Route::middleware(['admin'])->group(function () {
});

require __DIR__ . '/auth.php';
