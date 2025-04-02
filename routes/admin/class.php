<?php

use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;

Route::prefix('class')->name('class.')->group(function () {
    Route::get('/template', [ClassController::class, 'template'])->name('template');
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::post('/store', [ClassController::class, 'store'])->name('store');
    Route::get('/detail/{id}', [ClassController::class, 'detail'])->name('detail');
    Route::get('/edit/{id}', [ClassController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [ClassController::class, 'update'])->name('update');
    Route::get('/destroy/{id}', [ClassController::class, 'destroy'])->name('destroy');
    Route::get('/export/{id}', [ClassController::class, 'export'])->name('export');
});
