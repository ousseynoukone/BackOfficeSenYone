<?php

use App\Http\Controllers\API\Operations\LineController;
use App\Http\Controllers\API\Operations\UserLigneController;
use App\Http\Controllers\API\Operations\UserTrajetController;
use App\Http\Controllers\API\UserAuthApiController;
use App\Http\Controllers\DirectTrajetController;
use App\Http\Controllers\IndirectTrajetController;
use App\Http\Controllers\LigneApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LigneController;
use App\Http\Controllers\ProfileController;
use App\Mail\mailSender;
use Illuminate\Support\Facades\Mail;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/login', [UserAuthApiController::class, 'login'])->name('login');
Route::post('/reset', [UserAuthApiController::class, 'reset'])->name('reset');
Route::post('/reset-mail-sender', [UserAuthApiController::class, 'resetEmail'])->name('reset-mail-sender');
Route::post('/active-account-mail-sender', [UserAuthApiController::class, 'sendActiveAccountMail'])->name('active-account-mail-sender');
Route::post('/active-account', [UserAuthApiController::class, 'activeAccount'])->name('active-account');


Route::get('/logout', [UserAuthApiController::class, 'logout'])->name('logout');
Route::post('/register', [UserAuthApiController::class, 'register'])->name('register');




Route::middleware('auth:sanctum')->group(function () {
    Route::resource('/trajets',UserTrajetController::class);

    Route::resource('/lignes',UserLigneController::class);
    Route::post('/make-trajets',[UserTrajetController::class, 'makeTrajet']);

    Route::resource('/trajets-historiqueD',DirectTrajetController::class);
    Route::resource('/trajets-historiqueI',IndirectTrajetController::class);

});

