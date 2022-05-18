<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

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

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/new-attendece', [AttendanceController::class, 'create']);
    Route::post('/attendance/edit/{id}', [AttendanceController::class, 'edit']);
    Route::get('/show-attendece', [AttendanceController::class, 'show']);
    Route::get('/logout', [AuthController::class, 'logout']);
});
