<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return 'Hey We are working on it...';
})->name('home');
Route::get('/login', function () {
    return Inertia::render('auth/login');
})->name('login');

Route::prefix('app')->middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/settings.php';
