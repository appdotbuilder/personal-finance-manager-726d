<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\TransactionController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/health-check', function () {
    return response()->json([
        'status' => 'ok',
        'timestamp' => now()->toISOString(),
    ]);
})->name('health-check');

// Welcome page showcasing the personal finance app
Route::get('/', function () {
    return Inertia::render('welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard - main finance overview
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Account management
    Route::resource('accounts', AccountController::class);
    
    // Transaction management
    Route::resource('transactions', TransactionController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
