<?php

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DanaPaymentRequestController;
use App\Http\Controllers\DanaRequestController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExternalRetreatHallamController;
use App\Http\Controllers\ExternalRetreatPackenhamController;
use App\Http\Controllers\ExternalRetreatRequestFormGlenWaverleyController;
use App\Http\Controllers\FiveYearRequestController;
use App\Http\Controllers\FuturePlansRequestFormController;
use App\Http\Controllers\GilanPasaRequestController;
use App\Http\Controllers\InternalRetreatOrganiserRegistrationController;
use App\Http\Controllers\KatinaCeremonyRequestFormController;
use App\Http\Controllers\SermonRequestController;
use App\Http\Controllers\DanaAtHomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
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


Route::get('/admin/audit-logs', [AuditAdminLogController::class, 'index']);
Route::get('/user/audit-logs', [UserLogController::class, 'getAllLogs']);


///////////////////////////////////////////////////// ADMIN API /////////////////////////////////////////////////////
/// User API

Route::middleware('auth:sanctum')->post('/users', [UserController::class, 'getAllUsers']);
Route::middleware('auth:sanctum')->post('/create-user', [UserController::class, 'createUser']);
Route::middleware('auth:sanctum')->get('/get-all-users', [UserController::class, 'getAllUsers']);
Route::middleware('auth:sanctum')->get('/get-user/{id}', [UserController::class, 'getUserById']);
Route::middleware('auth:sanctum')->put('/edit-user/{id}', [UserController::class, 'editUser']);
Route::middleware('auth:sanctum')->delete('/soft-delete-user/{id}', [UserController::class, 'softDeleteUser']);


/// sermon-request API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-sermon-requests', [SermonRequestController::class, 'getAll']);
    Route::get('/get-sermon-request/{id}', [SermonRequestController::class, 'getById']);
    Route::put('/edit-sermon-request/{id}', [SermonRequestController::class, 'edit']);
    Route::delete('/delete-sermon-request/{id}', [SermonRequestController::class, 'softDelete']);
    Route::put('/sermon-request/{id}/status', [SermonRequestController::class, 'updateStatus']);
});


// Dana At Home API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-dana-at-home-requests', [DanaAtHomeController::class, 'getAll']);
    Route::get('/get-dana-at-home-request/{id}', [DanaAtHomeController::class, 'getById']);
    Route::put('/edit-dana-at-home-request/{id}', [DanaAtHomeController::class, 'edit']);
    Route::delete('/delete-dana-at-home-request/{id}', [DanaAtHomeController::class, 'softDelete']);
    Route::put('/dana-at-home-request/{id}/status', [DanaAtHomeController::class, 'updateStatus']);
});

// Dana Payment Request API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-dana-payment-requests', [DanaPaymentRequestController::class, 'getAll']);
    Route::get('/get-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'getById']);
    Route::put('/edit-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'edit']);
    Route::delete('/delete-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'softDelete']);
    Route::put('/dana-payment-request/{id}/status', [DanaPaymentRequestController::class, 'updateStatus']);
});

// Dana Request API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-dana-requests', [DanaRequestController::class, 'getAll']);
    Route::get('/get-dana-request/{id}', [DanaRequestController::class, 'getById']);
    Route::put('/edit-dana-request/{id}', [DanaRequestController::class, 'edit']);
    Route::delete('/delete-dana-request/{id}', [DanaRequestController::class, 'softDelete']);
    Route::put('/dana-request/{id}/status', [DanaRequestController::class, 'updateStatus']);
});

// External Retreat Request Form Glen Waverley API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-external-retreat-requests-gw', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getAll']);
    Route::get('/get-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getById']);
    Route::put('/edit-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'edit']);
    Route::delete('/delete-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'softDelete']);
    Route::put('/external-retreat-request-gw/{id}/status', [ExternalRetreatRequestFormGlenWaverleyController::class, 'updateStatus']);
});


// External Retreat Hallam API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-external-retreat-hallam-requests', [ExternalRetreatHallamController::class, 'getAll']);
    Route::get('/get-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'getById']);
    Route::put('/edit-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'edit']);
    Route::delete('/delete-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'softDelete']);
    Route::put('/external-retreat-hallam-request/{id}/status', [ExternalRetreatHallamController::class, 'updateStatus']);

});


// External Retreat Packenham API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-external-retreat-packenham-requests', [ExternalRetreatPackenhamController::class, 'getAll']);
    Route::get('/get-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'getById']);
    Route::put('/edit-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'edit']);
    Route::delete('/delete-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'softDelete']);
    Route::put('/external-retreat-packenham-request/{id}/status', [ExternalRetreatPackenhamController::class, 'updateStatus']);
});


// Future Plans Request Form API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-future-plans-requests', [FuturePlansRequestFormController::class, 'getAll']);
    Route::get('/get-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'getById']);
    Route::put('/edit-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'edit']);
    Route::delete('/delete-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'softDelete']);
    Route::put('/future-plans-request/{id}/status', [FuturePlansRequestFormController::class, 'updateStatus']);

});

// Five-Year Request API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-five-year-requests', [FiveYearRequestController::class, 'getAll']);
    Route::get('/get-five-year-request/{id}', [FiveYearRequestController::class, 'getById']);
    Route::put('/edit-five-year-request/{id}', [FiveYearRequestController::class, 'edit']);
    Route::delete('/delete-five-year-request/{id}', [FiveYearRequestController::class, 'softDelete']);
    Route::put('/five-year-request/{id}/status', [FiveYearRequestController::class, 'updateStatus']);

});


// Gilan Pasa Request API
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-gilan-pasa-requests', [GilanPasaRequestController::class, 'getAll']);
    Route::get('/get-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'getById']);
    Route::put('/edit-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'edit']);
    Route::delete('/delete-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'softDelete']);
    Route::put('/gilan-pasa-request/{id}/status', [GilanPasaRequestController::class, 'updateStatus']);

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

//External Retreat Request Form Hallam
Route::post('/external_retreat_request_form_hallam-request', [ExternalRetreatHallamController::class, 'store']);

//External Retreat Request Form Packenham
Route::post('/external_retreat_request_form_packenham-request', [ExternalRetreatPackenhamController::class, 'store']);

//Future Plans Request Form
Route::post('/future_plans_request_form-request', [FuturePlansRequestFormController::class, 'store']);

//Five-Year Request Form
Route::post('/five_year-request', [FiveYearRequestController::class, 'store']);

//Gilan Pasa Request Form
Route::post('/gilanpasa-request', [GilanPasaRequestController::class, 'store']);


// Event routes
Route::apiResource('events', EventController::class);
Route::post('events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
Route::get('events-list/with-trashed', [EventController::class, 'indexWithTrashed'])->name('events.with-trashed');



Route::prefix('katina-ceremony-requests')->group(function () {
    // Get all forms
    Route::get('/', [KatinaCeremonyRequestFormController::class, 'index']);

    // Create new form
    Route::post('/', [KatinaCeremonyRequestFormController::class, 'store']);

    // Get single form
    Route::get('/{id}', [KatinaCeremonyRequestFormController::class, 'show']);

    // Update form
    Route::put('/{id}', [KatinaCeremonyRequestFormController::class, 'update']);

    // Delete form
    Route::delete('/{id}', [KatinaCeremonyRequestFormController::class, 'destroy']);

    // Update status only
    Route::patch('/{id}/status', [KatinaCeremonyRequestFormController::class, 'updateStatus']);
});

Route::prefix('internal-retreat-organiser-registrations')->group(function () {
    // Get all registrations
    Route::get('/', [InternalRetreatOrganiserRegistrationController::class, 'index']);

    // Create new registration
    Route::post('/', [InternalRetreatOrganiserRegistrationController::class, 'store']);

    // Get single registration
    Route::get('/{id}', [InternalRetreatOrganiserRegistrationController::class, 'show']);

    // Update registration
    Route::put('/{id}', [InternalRetreatOrganiserRegistrationController::class, 'update']);

    // Delete registration
    Route::delete('/{id}', [InternalRetreatOrganiserRegistrationController::class, 'destroy']);

    // Update status only
    Route::patch('/{id}/status', [InternalRetreatOrganiserRegistrationController::class, 'updateStatus']);
});
