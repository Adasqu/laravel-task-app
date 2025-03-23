<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\TaskController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Ścieżki wymagające logowania
Route::middleware('auth')->group(function () {
    Route::resource('tasks', TaskController::class);
    Route::get('tasks/{task}/share', [TaskController::class, 'generateShareLink'])->name('tasks.share');
        Route::post('tasks/{task}/google-calendar', [TaskController::class, 'addToCalendar'])
         ->name('tasks.calendar');
});

// Publiczny dostęp do zadania przy pomocy tokenu
Route::get('public-task/{token}', [TaskController::class, 'showPublic'])->name('tasks.public');

require __DIR__ . '/auth.php';