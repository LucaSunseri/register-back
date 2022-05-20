<?php

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ExportDocumentController;
use App\Http\Controllers\SignatureController;
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
    Route::get('/attendance/index', [AttendanceController::class, 'index']);
    Route::get('/attendance/show/{id}', [AttendanceController::class, 'show']);
    Route::post('/attendance/create', [AttendanceController::class, 'create']);
    Route::post('/attendance/edit/{id}', [AttendanceController::class, 'edit']);

    Route::get('/logout', [AuthController::class, 'logout']);

    Route::get('/signature/check', [SignatureController::class, 'checkSignature']);
    Route::post('/signature/save', [SignatureController::class, 'saveSignature']);

    Route::post('/export/word', [ExportDocumentController::class, 'exportWord']);
});
