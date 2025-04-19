<?php

use App\Http\Controllers\QuizBankController;
use Illuminate\Support\Facades\Route;

Route::prefix('quiz-bank')->name('quizBank.')->group(function () {
    Route::get('/', [QuizBankController::class, 'index'])->name('index');
    Route::get('/questions/{id}', [QuizBankController::class, 'questions'])->name('questions');
    Route::post('/questions/store/{id}', [QuizBankController::class, 'questionsStore'])->name('question.store');
    Route::get('/questions/delete/{id}', [QuizBankController::class, 'questionDestroy'])->name('question.destroy');
    Route::post('/store', [QuizBankController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [QuizBankController::class, 'edit'])->name('edit');
    Route::put('/update/{id}', [QuizBankController::class, 'update'])->name('update');
});
