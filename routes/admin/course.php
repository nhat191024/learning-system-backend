<?php

use App\Http\Controllers\CourseController;
use Illuminate\Support\Facades\Route;

Route::prefix('course')->name('course.')->group(function () {
    Route::get('/', [CourseController::class, 'index'])->name('index');
    Route::post('/store', [CourseController::class, 'store'])->name('store');
    Route::get('/detail/{id}', [CourseController::class, 'detail'])->name('detail');
    Route::get('/edit/{id}', [CourseController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [CourseController::class, 'update'])->name('update');
    Route::get('/destroy/{id}', [CourseController::class, 'destroy'])->name('destroy');
    Route::get('/export/{id}', [CourseController::class, 'export'])->name('export');
});
