<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::prefix('/users')->name('users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/store', [UserController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
    Route::get('/destroy/{id}', [UserController::class, 'destroy'])->name('destroy');
});
