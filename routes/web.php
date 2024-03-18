<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\DeletedUserController;
use App\Http\Controllers\Web\UserController;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Support\Facades\Route;


Route::redirect('/', 'users');

Route::middleware(Authenticate::using('sanctum'))->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('users', UserController::class);
    Route::get('/deleted-users', [DeletedUserController::class, 'index'])->name('delted-users.index');
    Route::delete('/deleted-users/{user}', [DeletedUserController::class, 'destroy'])->name('delted-users.destroy');
    Route::patch('/deleted-users/{user}', [DeletedUserController::class, 'restore'])->name('delted-users.restore');
});

require __DIR__ . '/auth.php';
