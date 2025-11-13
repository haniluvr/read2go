<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PenaltyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReturnController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Books routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/search', [BookController::class, 'search'])->name('books.search');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Loans routes (authenticated)
Route::middleware('auth')->group(function () {
    Route::get('/loans', [LoanController::class, 'index'])->name('loans.index');
    Route::get('/loans/create/{book}', [LoanController::class, 'create'])->name('loans.create');
    Route::post('/loans/{book}', [LoanController::class, 'store'])->name('loans.store');
    Route::get('/loans/{loan}', [LoanController::class, 'show'])->name('loans.show');

    // Returns routes
    Route::get('/returns/create/{loan}', [ReturnController::class, 'create'])->name('returns.create');
    Route::post('/returns/{loan}', [ReturnController::class, 'store'])->name('returns.store');
    Route::post('/returns/{loan}/confirm', [ReturnController::class, 'confirm'])->name('returns.confirm');

    // Payments routes
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::post('/payments/delivery/{loanId}', [PaymentController::class, 'createForDelivery'])->name('payments.create.delivery');
    Route::post('/payments/penalty/{penaltyId}', [PaymentController::class, 'createForPenalty'])->name('payments.create.penalty');
    Route::get('/payments/success', [PaymentController::class, 'success'])->name('payments.success');
    Route::get('/payments/failure', [PaymentController::class, 'failure'])->name('payments.failure');

    // Penalties routes
    Route::get('/penalties', [PenaltyController::class, 'index'])->name('penalties.index');
    Route::get('/penalties/{penalty}', [PenaltyController::class, 'show'])->name('penalties.show');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Notifications routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
});

// Xendit webhook (no auth required)
Route::post('/webhooks/xendit', [PaymentController::class, 'webhook'])->name('webhooks.xendit');

// Admin routes
Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('admin.dashboard');
    
    Route::get('/loans', [App\Http\Controllers\Admin\LoanController::class, 'index'])->name('admin.loans.index');
    Route::get('/loans/{loan}', [App\Http\Controllers\Admin\LoanController::class, 'show'])->name('admin.loans.show');
    
    Route::get('/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'show'])->name('admin.users.show');
    Route::post('/users/{user}/suspend', [App\Http\Controllers\Admin\UserController::class, 'suspend'])->name('admin.users.suspend');
    Route::post('/users/{user}/unsuspend', [App\Http\Controllers\Admin\UserController::class, 'unsuspend'])->name('admin.users.unsuspend');
    
    Route::get('/penalties', [App\Http\Controllers\Admin\PenaltyController::class, 'index'])->name('admin.penalties.index');
    Route::get('/penalties/{penalty}', [App\Http\Controllers\Admin\PenaltyController::class, 'show'])->name('admin.penalties.show');
    
    Route::get('/deliveries', [App\Http\Controllers\Admin\DeliveryController::class, 'index'])->name('admin.deliveries.index');
    Route::post('/deliveries/{schedule}/status', [App\Http\Controllers\Admin\DeliveryController::class, 'updateStatus'])->name('admin.deliveries.update-status');
    Route::post('/returns/{loan}/confirm', [App\Http\Controllers\ReturnController::class, 'confirm'])->name('admin.returns.confirm');
});

require __DIR__.'/auth.php';
