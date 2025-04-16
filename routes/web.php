<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->middleware(['auth', 'verified'])->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    require __DIR__ . '/admin/user.php';
    require __DIR__ . '/admin/class.php';
    require __DIR__ . '/admin/student.php';
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

//caddy verify, this route is used to verify the domain with caddy and always return 200
Route::get('/caddy/verify', function () {
    return response()->json(['status' => 'ok']);
})->name('caddy.verify');

require __DIR__ . '/auth.php';
