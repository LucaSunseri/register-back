<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportDocumentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/activity', [ActivityController::class, 'getAll']);

Route::post('/role/create', [RoleController::class, 'create']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/attendance/index', [AttendanceController::class, 'index']);
    Route::get('/attendance/show/{id}', [AttendanceController::class, 'show']);
    Route::post('/attendance/create', [AttendanceController::class, 'create']);
    Route::post('/attendance/edit/{id}', [AttendanceController::class, 'edit']);

    Route::get('/signature/check', [SignatureController::class, 'checkSignature']);
    Route::post('/signature/save', [SignatureController::class, 'saveSignature']);

    Route::get('/logout', [AuthController::class, 'logout']);

    // Route sotto sanctum e il ruolo di super-admin o tutor
    Route::group(['middleware' => ['role:super-admin|tutor']], function () {
        Route::get('/user/developer', [UserController::class, 'getAllDeveloperUser']);
        Route::post('/export/word', [ExportDocumentController::class, 'exportWord']);

        Route::post('/activity/create', [ActivityController::class, 'create']);
        Route::post('/activity/edit/{id}', [ActivityController::class, 'edit']);
    });

    // Route sotto sanctum e il ruolo di super-admin
    Route::group(['middleware' => ['role:super-admin']], function () {
        Route::post('/role/create', [RoleController::class, 'create']);
        Route::post('/role/assign', [RoleController::class, 'assign']);
        Route::post('/role/remove', [RoleController::class, 'remove']);

        Route::post('/permission/create', [PermissionController::class, 'create']);
        Route::post('/permission/assign', [PermissionController::class, 'assign']);
        Route::post('/permission/remove', [PermissionController::class, 'remove']);
    });
});
