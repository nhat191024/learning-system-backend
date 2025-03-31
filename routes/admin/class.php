<?php

use App\Http\Controllers\ClassController;
use Illuminate\Support\Facades\Route;

Route::prefix('class')->name('class.')->group(function () {
    Route::get('/template', [ClassController::class, 'template'])->name('template');
    Route::get('/', [ClassController::class, 'index'])->name('index');
    Route::post('/store', [ClassController::class, 'store'])->name('store');

    Route::get('/hide-class/{class_id}', [ClassController::class, 'hideClass'])->name('hideClass');
    Route::get('/edit-class/{class_id}', [ClassController::class, 'editClass'])->name('editClass');
    Route::put('/update-class/{class_id}', [ClassController::class, 'updateClass'])->name('updateClass');
    Route::get('/{id}', [ClassController::class, 'show'])->name('show');
    Route::get('/{id}/assignment/{assignment_id}', [ClassController::class, 'show'])->name('assignmentDetails');
    Route::get('/assignment/{assignment_id}/details', [ClassController::class, 'assignmentDetailsJson'])->name('assignmentDetailsJson');
    Route::post('/{id}/toggle-status', [ClassController::class, 'toggleClassStatus'])->name('toggleStatus');
    Route::get('/{id}/export', [ClassController::class, 'export'])->name('export');
    Route::post('/{class}/import-confirm', [ClassController::class, 'importConfirm'])->name('importConfirm');
});
