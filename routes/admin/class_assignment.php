<?php

use App\Http\Controllers\ClassAssignmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('class-assignment')->name('classAssignment.')->group(function () {
    Route::get('/detail/{id}/{classId}', [ClassAssignmentController::class, 'detail'])->name('detail');
    route::post('/store/{classId}', [ClassAssignmentController::class, 'store'])->name('store');
    Route::get('/destroy/{id}', [ClassAssignmentController::class, 'destroy'])->name('destroy');
});
