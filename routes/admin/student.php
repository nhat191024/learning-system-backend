<?php

use App\Http\Controllers\StudentController;
use Illuminate\Support\Facades\Route;

Route::prefix('/student')->name('student.')->group(function () {
    Route::post('/store', [StudentController::class, 'store'])->name('store');

    Route::delete('/remove-student/{class_id}/{student_id}', [StudentController::class, 'removeStudentFromAClass'])->name('removeStudent');
});
