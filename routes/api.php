<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ExportDocumentController;
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

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/signature/check', [SignatureController::class, 'checkSignature']);
    Route::post('/signature/save', [SignatureController::class, 'saveSignature']);

    Route::group(['middleware' => ['role:super-admin|tutor']], function () {
        Route::get('/user/developer', [UserController::class, 'getAllDeveloperUser']);
        Route::post('/export/word', [ExportDocumentController::class, 'exportWord']);
    });
});
