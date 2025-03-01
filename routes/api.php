<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DanaPaymentRequestController;
use App\Http\Controllers\DanaRequestController;
use App\Http\Controllers\ExternalRetreatHallamController;
use App\Http\Controllers\ExternalRetreatRequestFormGlenWaverleyController;
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
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

///////////////////////////////////////////////////// ADMIN API /////////////////////////////////////////////////////

Route::middleware('auth:sanctum')->post('/users', [UserController::class, 'getAllUsers']);
Route::middleware('auth:sanctum')->post('/create-user', [UserController::class, 'createUser']);


Route::middleware('auth:api')->group(function () {
});


///////////////////////////////////////////////////// PUBLIC API /////////////////////////////////////////////////////

//Damma Sermons API
Route::post('/sermon-request', [SermonRequestController::class, 'store']);

//Dana At Home API
Route::post('/danaAtHome-request', [DanaAtHomeController::class, 'store']);

//Dana Payment Request API
Route::post('/danaPayment-request', [DanaPaymentRequestController::class, 'store']);

//Dana Request API
Route::post('/dana-request', [DanaRequestController::class, 'store']);

//External Retreat Request Form Glen Waverley
Route::post('/external_retreat_request_form_glen_waverley-request', [ExternalRetreatRequestFormGlenWaverleyController::class, 'store']);

//External Retreat Request Form Glen Waverley
Route::post('/external_retreat_request_form_hallam-request', [ExternalRetreatHallamController::class, 'store']);




