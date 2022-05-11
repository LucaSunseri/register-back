<?php

use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
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



Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/new-attendece', [AttendanceController::class, 'create']);
    Route::post('/new-attendece/edit/{id}', [AttendanceController::class, 'edit']);
    Route::get('/show-attendece', [AttendanceController::class, 'show']);
});
