<?php

use App\Http\Controllers\API\CouponController;
use App\Http\Controllers\API\EventController;
use App\Http\Controllers\API\LotController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\PaymentController;
use App\Http\Controllers\API\RolePermissionController;
use App\Http\Controllers\API\SectorController;
use App\Http\Controllers\API\TeamController;
use App\Http\Controllers\API\TicketController;
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

Route::webhooks('webhook-receiving-url');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('team', [AuthController::class, 'changeTeam']);

    Route::middleware(['teams_permission'])->group(function () {
        Route::apiResource('users', UserController::class);
        Route::apiResource('events', EventController::class);
        Route::apiResource('sectors', SectorController::class);
        Route::apiResource('lots', LotController::class);

        Route::get('tickets/{id}', [TicketController::class, 'show']);
        Route::get('tickets', [TicketController::class, 'index']);

        Route::apiResource('orders', OrderController::class);
        Route::apiResource('payments', PaymentController::class);

        // Teams
        Route::post('teams/invite', [TeamController::class, 'inviteUser']);
        Route::delete('teams/withdraw', [TeamController::class, 'withdrawUser']);
        Route::apiResource('teams', TeamController::class);

        // Coupons
        Route::apiResource('coupons', CouponController::class);
    });

    // Roles
    Route::get('/roles', [RolePermissionController::class, 'getRoles']);
    Route::post('/roles', [RolePermissionController::class, 'createRole']);
    Route::delete('/roles/{role}', [RolePermissionController::class, 'deleteRole']);

    // Permissions
    Route::get('/permissions', 'API\RolePermissionController@getPermissions');
    Route::post('/permissions', 'API\RolePermissionController@createPermission');
    Route::delete('/permissions/{permission}', 'API\RolePermissionController@deletePermission');

    // Attach/Detach Permissions to/from Roles
    Route::put('/roles/{role}/permissions', 'API\RolePermissionController@attachPermissions');
    Route::delete('/roles/{role}/permissions', 'API\RolePermissionController@detachPermissions');
});

//Route::resource('/users', 'App\Http\Controllers\UserController');
