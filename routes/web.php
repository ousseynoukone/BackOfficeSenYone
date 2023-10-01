<?php

use App\Http\Controllers\LigneController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\usagerController;
use App\Http\Controllers\userController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/dashboard', function () {
    return redirect()->route('ligne.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/', function () {
    return redirect()->route('ligne.index');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('ligne',LigneController::class);
    Route::resource('admins',userController::class);
    Route::resource('usagers',usagerController::class);
    Route::post('/admins-reset/{id}',[userController::class, 'reset'])->name('admins.reset');
});

require __DIR__.'/auth.php';
