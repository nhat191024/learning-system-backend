<?php

use App\Http\Controllers\CourseAssignmentController;
use Illuminate\Support\Facades\Route;

Route::prefix('course-assignment')->name('courseAssignment.')->group(function () {
    Route::get('/detail/{id}/{courseId}', [CourseAssignmentController::class, 'detail'])->name('detail');
    route::post('/store/{classId}', [CourseAssignmentController::class, 'store'])->name('store');
    Route::get('/destroy/{id}', [CourseAssignmentController::class, 'destroy'])->name('destroy');
});
