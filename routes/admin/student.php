<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/student')->name('student.')->group(function () {
    Route::post('/store/{id}', [StudentController::class, 'store'])->name('store');
    Route::get('/destroy/{class_id}/{student_id}', [StudentController::class, 'destroy'])->name('destroy');
    Route::get('/certificate/{class_id}/{student_id}', [StudentController::class, 'certificate'])->name('certificate');
    Route::get('/certificate/delete/{class_id}/{student_id}', [StudentController::class, 'destroyCertificate'])->name('destroyCertificate');
});
