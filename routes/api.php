<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\AutoEcoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });





Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
});

Route::group(["middleware" => "auth:api"], function () {
    Route::get('refresh', [AuthController::class, 'refreshToken'])->name("refreshToken");
    Route::get('logout', [AuthController::class, 'logout'])->name("logout");


    Route::controller(AutoEcoleController::class)->group(function () {
        Route::get('autoEcoles', 'index')->name('list_auto_ecole');
        Route::get('autoEcoles/{autoEcole}', 'show')->name('show_auto_ecole');
        Route::post('autoEcoles', 'store')->name('create_auto_ecole');
        Route::put('autoEcoles/{autoEcole}', 'update')->name('update_auto_ecole');
        Route::delete('autoEcoles', 'destroy')->name('delete_auto_ecole');
    });
});
