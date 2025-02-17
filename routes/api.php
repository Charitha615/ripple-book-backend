<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\SermonRequestController;
use App\Http\Controllers\DanaAtHomeController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/users', [UserController::class, 'getAllUsers']);
Route::middleware('auth:sanctum')->post('/create-user', [UserController::class, 'createUser']);

//Sermon API
Route::post('/sermon-request', [SermonRequestController::class, 'store']);
Route::post('/danaAtHome-request', [DanaAtHomeController::class, 'store']);

Route::middleware('auth:api')->group(function () {
});
