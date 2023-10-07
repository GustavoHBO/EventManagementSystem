<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
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

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'signup']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum', 'auth'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::middleware(['teams_permission'])->group(function () {
//        Route::get('users', [UserController::class, 'index']);
        Route::apiResource('users', UserController::class);
    });

});

//Route::resource('/users', 'App\Http\Controllers\UserController');
