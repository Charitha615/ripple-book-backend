<?php

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DanaPaymentRequestController;
use App\Http\Controllers\DanaRequestController;
use App\Http\Controllers\DhammaSermonRequestController;
use App\Http\Controllers\DhammaTalkController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExternalRetreatHallamController;
use App\Http\Controllers\ExternalRetreatPackenhamController;
use App\Http\Controllers\ExternalRetreatRequestFormGlenWaverleyController;
use App\Http\Controllers\FiveYearRequestController;
use App\Http\Controllers\FuturePlansRequestFormController;
use App\Http\Controllers\GilanPasaRequestController;
use App\Http\Controllers\GuestSpeakerRequestController;
use App\Http\Controllers\InternalRetreatOrganiserRegistrationController;
use App\Http\Controllers\KatinaCeremonyRequestFormController;
use App\Http\Controllers\ParittaAtHomeRequestFormController;
use App\Http\Controllers\SermonRequestController;
use App\Http\Controllers\DanaAtHomeController;
use App\Http\Controllers\SoloRetreatController;
use App\Http\Controllers\SoloRetreatRegistrationController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserLogController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| All API routes are registered here.
| Public APIs are accessible without login.
| Admin APIs are protected with sanctum authentication middleware.
|
*/

/*
|--------------------------------------------------------------------------
| AUTHENTICATION
|--------------------------------------------------------------------------
*/
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Get authenticated user
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| AUDIT LOGS
|--------------------------------------------------------------------------
*/
Route::get('/admin/audit-logs', [AuditAdminLogController::class, 'index']);  // Admin audit logs
Route::get('/user/audit-logs', [UserLogController::class, 'getAllLogs']);   // User activity logs


/*
|--------------------------------------------------------------------------
| ADMIN APIs (Protected)
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | USERS
    |--------------------------------------------------------------------------
    */
    Route::post('/users', [UserController::class, 'getAllUsers']);
    Route::post('/create-user', [UserController::class, 'createUser']);
    Route::get('/get-all-users', [UserController::class, 'getAllUsers']);
    Route::get('/get-user/{id}', [UserController::class, 'getUserById']);
    Route::put('/edit-user/{id}', [UserController::class, 'editUser']);
    Route::delete('/soft-delete-user/{id}', [UserController::class, 'softDeleteUser']);


    /*
    |--------------------------------------------------------------------------
    | SERMON REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-sermon-requests', [SermonRequestController::class, 'getAll']);
    Route::get('/get-sermon-request/{id}', [SermonRequestController::class, 'getById']);
    Route::put('/edit-sermon-request/{id}', [SermonRequestController::class, 'edit']);
    Route::delete('/delete-sermon-request/{id}', [SermonRequestController::class, 'softDelete']);
    Route::put('/sermon-request/{id}/status', [SermonRequestController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | DANA AT HOME REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-dana-at-home-requests', [DanaAtHomeController::class, 'getAll']);
    Route::get('/get-dana-at-home-request/{id}', [DanaAtHomeController::class, 'getById']);
    Route::put('/edit-dana-at-home-request/{id}', [DanaAtHomeController::class, 'edit']);
    Route::delete('/delete-dana-at-home-request/{id}', [DanaAtHomeController::class, 'softDelete']);
    Route::put('/dana-at-home-request/{id}/status', [DanaAtHomeController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | DANA PAYMENT REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-dana-payment-requests', [DanaPaymentRequestController::class, 'getAll']);
    Route::get('/get-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'getById']);
    Route::put('/edit-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'edit']);
    Route::delete('/delete-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'softDelete']);
    Route::put('/dana-payment-request/{id}/status', [DanaPaymentRequestController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | DANA REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-dana-requests', [DanaRequestController::class, 'getAll']);
    Route::get('/get-dana-request/{id}', [DanaRequestController::class, 'getById']);
    Route::put('/edit-dana-request/{id}', [DanaRequestController::class, 'edit']);
    Route::delete('/delete-dana-request/{id}', [DanaRequestController::class, 'softDelete']);
    Route::put('/dana-request/{id}/status', [DanaRequestController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | EXTERNAL RETREAT REQUESTS - Glen Waverley
    |--------------------------------------------------------------------------
    */
    Route::get('/get-external-retreat-requests-gw', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getAll']);
    Route::get('/get-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getById']);
    Route::put('/edit-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'edit']);
    Route::delete('/delete-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'softDelete']);
    Route::put('/external-retreat-request-gw/{id}/status', [ExternalRetreatRequestFormGlenWaverleyController::class, 'updateStatus']);

    /*
    |--------------------------------------------------------------------------
    | EXTERNAL RETREAT REQUESTS - Hallam
    |--------------------------------------------------------------------------
    */
    Route::get('/get-external-retreat-hallam-requests', [ExternalRetreatHallamController::class, 'getAll']);
    Route::get('/get-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'getById']);
    Route::put('/edit-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'edit']);
    Route::delete('/delete-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'softDelete']);
    Route::put('/external-retreat-hallam-request/{id}/status', [ExternalRetreatHallamController::class, 'updateStatus']);

    /*
    |--------------------------------------------------------------------------
    | EXTERNAL RETREAT REQUESTS - Packenham
    |--------------------------------------------------------------------------
    */
    Route::get('/get-external-retreat-packenham-requests', [ExternalRetreatPackenhamController::class, 'getAll']);
    Route::get('/get-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'getById']);
    Route::put('/edit-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'edit']);
    Route::delete('/delete-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'softDelete']);
    Route::put('/external-retreat-packenham-request/{id}/status', [ExternalRetreatPackenhamController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | FUTURE PLANS REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-future-plans-requests', [FuturePlansRequestFormController::class, 'getAll']);
    Route::get('/get-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'getById']);
    Route::put('/edit-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'edit']);
    Route::delete('/delete-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'softDelete']);
    Route::put('/future-plans-request/{id}/status', [FuturePlansRequestFormController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | FIVE-YEAR REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-five-year-requests', [FiveYearRequestController::class, 'getAll']);
    Route::get('/get-five-year-request/{id}', [FiveYearRequestController::class, 'getById']);
    Route::put('/edit-five-year-request/{id}', [FiveYearRequestController::class, 'edit']);
    Route::delete('/delete-five-year-request/{id}', [FiveYearRequestController::class, 'softDelete']);
    Route::put('/five-year-request/{id}/status', [FiveYearRequestController::class, 'updateStatus']);


    /*
    |--------------------------------------------------------------------------
    | GILAN PASA REQUESTS
    |--------------------------------------------------------------------------
    */
    Route::get('/get-gilan-pasa-requests', [GilanPasaRequestController::class, 'getAll']);
    Route::get('/get-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'getById']);
    Route::put('/edit-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'edit']);
    Route::delete('/delete-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'softDelete']);
    Route::put('/gilan-pasa-request/{id}/status', [GilanPasaRequestController::class, 'updateStatus']);

    /*
    |--------------------------------------------------------------------------
    | Event REQUESTS
    |--------------------------------------------------------------------------
    */

    Route::post('events', [EventController::class, 'store']);
    Route::put('events/{id}', [EventController::class, 'update']);
    Route::delete('events/{id}', [EventController::class, 'destroy']);
    Route::post('events/{id}/restore', [EventController::class, 'restore']);


    Route::put('edit-paritta-at-home-requests/{id}', [ParittaAtHomeRequestFormController::class, 'apiUpdate']);
    Route::patch('edit-paritta-at-home-requests/{id}/status', [ParittaAtHomeRequestFormController::class, 'apiUpdateStatus']);
    Route::delete('delete-paritta-at-home-requests/{id}', [ParittaAtHomeRequestFormController::class, 'apiDestroy']);


    Route::put('edit-dhamma-sermon-requests/{id}', [DhammaSermonRequestController::class, 'apiUpdate']);
    Route::patch('edit-dhamma-sermon-requests/{id}/status', [DhammaSermonRequestController::class, 'apiUpdateStatus']);
    Route::delete('delete-dhamma-sermon-requests/{id}', [DhammaSermonRequestController::class, 'apiDestroy']);
});


/*
|--------------------------------------------------------------------------
| PUBLIC APIs (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Sermon Requests
Route::post('/sermon-request', [SermonRequestController::class, 'store']);

// Dana At Home
Route::post('/danaAtHome-request', [DanaAtHomeController::class, 'store']);

// Dana Payment
Route::post('/danaPayment-request', [DanaPaymentRequestController::class, 'store']);

// Dana Request
Route::post('/dana-request', [DanaRequestController::class, 'store']);

// External Retreat - Glen Waverley
Route::post('/external_retreat_request_form_glen_waverley-request', [ExternalRetreatRequestFormGlenWaverleyController::class, 'store']);

// External Retreat - Hallam
Route::post('/external_retreat_request_form_hallam-request', [ExternalRetreatHallamController::class, 'store']);

// External Retreat - Packenham
Route::post('/external_retreat_request_form_packenham-request', [ExternalRetreatPackenhamController::class, 'store']);

// Future Plans
Route::post('/future_plans_request_form-request', [FuturePlansRequestFormController::class, 'store']);

// Five-Year Plan
Route::post('/five_year-request', [FiveYearRequestController::class, 'store']);

// Gilan Pasa
Route::post('/gilanpasa-request', [GilanPasaRequestController::class, 'store']);


/*
|--------------------------------------------------------------------------
| EVENTS
|--------------------------------------------------------------------------
*/

Route::get('events', [EventController::class, 'index']);
Route::get('events/{id}', [EventController::class, 'show']);
Route::get('events-list/with-trashed', [EventController::class, 'indexWithTrashed']);


/*
|--------------------------------------------------------------------------
| KATINA CEREMONY REQUESTS
|--------------------------------------------------------------------------
*/
Route::get('get-katina-ceremony-requests', [KatinaCeremonyRequestFormController::class, 'index']);
Route::post('create-katina-ceremony-requests', [KatinaCeremonyRequestFormController::class, 'store']);
Route::get('get-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'show']);
Route::put('update-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'update']);
Route::delete('delete-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'destroy']);
Route::patch('katina-ceremony-requests/{id}/status', [KatinaCeremonyRequestFormController::class, 'updateStatus']);


/*
|--------------------------------------------------------------------------
| INTERNAL RETREAT ORGANISER REGISTRATIONS
|--------------------------------------------------------------------------
*/
Route::get('get-internal-retreat-organiser-registrations', [InternalRetreatOrganiserRegistrationController::class, 'index']);
Route::post('create-internal-retreat-organiser-registrations', [InternalRetreatOrganiserRegistrationController::class, 'store']);
Route::get('get-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'show']);
Route::put('update-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'update']);
Route::delete('delete-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'destroy']);
Route::patch('internal-retreat-organiser-registrations/{id}/status', [InternalRetreatOrganiserRegistrationController::class, 'updateStatus']);


/*
|--------------------------------------------------------------------------
| SOLO RETREAT REGISTRATIONS
|--------------------------------------------------------------------------
*/
Route::get('get-solo-retreat-registrations', [SoloRetreatRegistrationController::class, 'index']);
Route::post('create-solo-retreat-registrations', [SoloRetreatRegistrationController::class, 'store']);
Route::get('get-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'show']);
Route::put('update-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'update']);
Route::delete('delete-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'destroy']);
Route::patch('solo-retreat-registrations/{id}/status', [SoloRetreatRegistrationController::class, 'updateStatus']);
Route::get('get-solo-retreat-registrations/status/{status}', [SoloRetreatRegistrationController::class, 'getByStatus']);


/*
|--------------------------------------------------------------------------
| SOLO RETREATS
|--------------------------------------------------------------------------
*/
Route::get('get-solo-retreats', [SoloRetreatController::class, 'index']);
Route::post('create-solo-retreats', [SoloRetreatController::class, 'store']);
Route::get('get-solo-retreats/{id}', [SoloRetreatController::class, 'show']);
Route::put('update-solo-retreats/{id}', [SoloRetreatController::class, 'update']);
Route::delete('delete-solo-retreats/{id}', [SoloRetreatController::class, 'destroy']);
Route::patch('solo-retreats/{id}/status', [SoloRetreatController::class, 'updateStatus']);
Route::get('get-solo-retreats/status/{status}', [SoloRetreatController::class, 'getByStatus']);
Route::get('get-solo-retreats/stats', [SoloRetreatController::class, 'getStats']);


/*
|--------------------------------------------------------------------------
| GUEST SPEAKER REQUESTS
|--------------------------------------------------------------------------
*/
Route::get('get-guest-speaker-requests', [GuestSpeakerRequestController::class, 'index']);
Route::post('create-guest-speaker-requests', [GuestSpeakerRequestController::class, 'store']);
Route::get('get-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'show']);
Route::put('update-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'update']);
Route::delete('delete-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'destroy']);
Route::patch('guest-speaker-requests/{id}/status', [GuestSpeakerRequestController::class, 'updateStatus']);
Route::get('get-guest-speaker-requests/status/{status}', [GuestSpeakerRequestController::class, 'getByStatus']);
Route::get('get-guest-speaker-requests/stats', [GuestSpeakerRequestController::class, 'getStats']);


/*
|--------------------------------------------------------------------------
| DHAMMA TALKS
|--------------------------------------------------------------------------
*/
Route::get('dhamma-talks', [DhammaTalkController::class, 'index']);
Route::post('dhamma-talks', [DhammaTalkController::class, 'store']);
Route::get('dhamma-talks/{id}', [DhammaTalkController::class, 'show']);
Route::put('dhamma-talks/{id}', [DhammaTalkController::class, 'update']);
Route::delete('dhamma-talks/{id}', [DhammaTalkController::class, 'destroy']);
Route::get('dhamma-talks/search', [DhammaTalkController::class, 'search']);


Route::get('get-paritta-at-home-requests/', [ParittaAtHomeRequestFormController::class, 'apiIndex']);
Route::get('get-paritta-at-home-requests/{id}', [ParittaAtHomeRequestFormController::class, 'apiShow']);
Route::post('create-paritta-at-home-requests/', [ParittaAtHomeRequestFormController::class, 'apiStore']);



Route::get('get-dhamma-sermon-requests/', [DhammaSermonRequestController::class, 'apiIndex']);
Route::get('get-dhamma-sermon-requests/{id}', [DhammaSermonRequestController::class, 'apiShow']);
Route::post('create-dhamma-sermon-requests/', [DhammaSermonRequestController::class, 'apiStore']);




//
///*
//|--------------------------------------------------------------------------
//| API Routes
//|--------------------------------------------------------------------------
//|
//| Here is where you can register API routes for your application. These
//| routes are loaded by the RouteServiceProvider and all of them will
//| be assigned to the "api" middleware group. Make something great!
//|
//*/
//
//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});
//
//Route::post('register', [AuthController::class, 'register']);
//Route::post('login', [AuthController::class, 'login']);
//Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
//
//
//Route::get('/admin/audit-logs', [AuditAdminLogController::class, 'index']);
//Route::get('/user/audit-logs', [UserLogController::class, 'getAllLogs']);
//
//
/////////////////////////////////////////////////////// ADMIN API /////////////////////////////////////////////////////
///// User API
//
//Route::middleware('auth:sanctum')->post('/users', [UserController::class, 'getAllUsers']);
//Route::middleware('auth:sanctum')->post('/create-user', [UserController::class, 'createUser']);
//Route::middleware('auth:sanctum')->get('/get-all-users', [UserController::class, 'getAllUsers']);
//Route::middleware('auth:sanctum')->get('/get-user/{id}', [UserController::class, 'getUserById']);
//Route::middleware('auth:sanctum')->put('/edit-user/{id}', [UserController::class, 'editUser']);
//Route::middleware('auth:sanctum')->delete('/soft-delete-user/{id}', [UserController::class, 'softDeleteUser']);
//
//
///// sermon-request API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-sermon-requests', [SermonRequestController::class, 'getAll']);
//    Route::get('/get-sermon-request/{id}', [SermonRequestController::class, 'getById']);
//    Route::put('/edit-sermon-request/{id}', [SermonRequestController::class, 'edit']);
//    Route::delete('/delete-sermon-request/{id}', [SermonRequestController::class, 'softDelete']);
//    Route::put('/sermon-request/{id}/status', [SermonRequestController::class, 'updateStatus']);
//});
//
//
//// Dana At Home API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-dana-at-home-requests', [DanaAtHomeController::class, 'getAll']);
//    Route::get('/get-dana-at-home-request/{id}', [DanaAtHomeController::class, 'getById']);
//    Route::put('/edit-dana-at-home-request/{id}', [DanaAtHomeController::class, 'edit']);
//    Route::delete('/delete-dana-at-home-request/{id}', [DanaAtHomeController::class, 'softDelete']);
//    Route::put('/dana-at-home-request/{id}/status', [DanaAtHomeController::class, 'updateStatus']);
//});
//
//// Dana Payment Request API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-dana-payment-requests', [DanaPaymentRequestController::class, 'getAll']);
//    Route::get('/get-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'getById']);
//    Route::put('/edit-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'edit']);
//    Route::delete('/delete-dana-payment-request/{id}', [DanaPaymentRequestController::class, 'softDelete']);
//    Route::put('/dana-payment-request/{id}/status', [DanaPaymentRequestController::class, 'updateStatus']);
//});
//
//// Dana Request API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-dana-requests', [DanaRequestController::class, 'getAll']);
//    Route::get('/get-dana-request/{id}', [DanaRequestController::class, 'getById']);
//    Route::put('/edit-dana-request/{id}', [DanaRequestController::class, 'edit']);
//    Route::delete('/delete-dana-request/{id}', [DanaRequestController::class, 'softDelete']);
//    Route::put('/dana-request/{id}/status', [DanaRequestController::class, 'updateStatus']);
//});
//
//// External Retreat Request Form Glen Waverley API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-external-retreat-requests-gw', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getAll']);
//    Route::get('/get-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'getById']);
//    Route::put('/edit-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'edit']);
//    Route::delete('/delete-external-retreat-request-gw/{id}', [ExternalRetreatRequestFormGlenWaverleyController::class, 'softDelete']);
//    Route::put('/external-retreat-request-gw/{id}/status', [ExternalRetreatRequestFormGlenWaverleyController::class, 'updateStatus']);
//});
//
//
//// External Retreat Hallam API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-external-retreat-hallam-requests', [ExternalRetreatHallamController::class, 'getAll']);
//    Route::get('/get-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'getById']);
//    Route::put('/edit-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'edit']);
//    Route::delete('/delete-external-retreat-hallam-request/{id}', [ExternalRetreatHallamController::class, 'softDelete']);
//    Route::put('/external-retreat-hallam-request/{id}/status', [ExternalRetreatHallamController::class, 'updateStatus']);
//
//});
//
//
//// External Retreat Packenham API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-external-retreat-packenham-requests', [ExternalRetreatPackenhamController::class, 'getAll']);
//    Route::get('/get-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'getById']);
//    Route::put('/edit-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'edit']);
//    Route::delete('/delete-external-retreat-packenham-request/{id}', [ExternalRetreatPackenhamController::class, 'softDelete']);
//    Route::put('/external-retreat-packenham-request/{id}/status', [ExternalRetreatPackenhamController::class, 'updateStatus']);
//});
//
//
//// Future Plans Request Form API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-future-plans-requests', [FuturePlansRequestFormController::class, 'getAll']);
//    Route::get('/get-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'getById']);
//    Route::put('/edit-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'edit']);
//    Route::delete('/delete-future-plans-request/{id}', [FuturePlansRequestFormController::class, 'softDelete']);
//    Route::put('/future-plans-request/{id}/status', [FuturePlansRequestFormController::class, 'updateStatus']);
//
//});
//
//// Five-Year Request API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-five-year-requests', [FiveYearRequestController::class, 'getAll']);
//    Route::get('/get-five-year-request/{id}', [FiveYearRequestController::class, 'getById']);
//    Route::put('/edit-five-year-request/{id}', [FiveYearRequestController::class, 'edit']);
//    Route::delete('/delete-five-year-request/{id}', [FiveYearRequestController::class, 'softDelete']);
//    Route::put('/five-year-request/{id}/status', [FiveYearRequestController::class, 'updateStatus']);
//
//});
//
//
//// Gilan Pasa Request API
//Route::middleware('auth:sanctum')->group(function () {
//    Route::get('/get-gilan-pasa-requests', [GilanPasaRequestController::class, 'getAll']);
//    Route::get('/get-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'getById']);
//    Route::put('/edit-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'edit']);
//    Route::delete('/delete-gilan-pasa-request/{id}', [GilanPasaRequestController::class, 'softDelete']);
//    Route::put('/gilan-pasa-request/{id}/status', [GilanPasaRequestController::class, 'updateStatus']);
//
//});
//
/////////////////////////////////////////////////////// PUBLIC API /////////////////////////////////////////////////////
//
////Damma Sermons API
//Route::post('/sermon-request', [SermonRequestController::class, 'store']);
//
////Dana At Home API
//Route::post('/danaAtHome-request', [DanaAtHomeController::class, 'store']);
//
////Dana Payment Request API
//Route::post('/danaPayment-request', [DanaPaymentRequestController::class, 'store']);
//
////Dana Request API
//Route::post('/dana-request', [DanaRequestController::class, 'store']);
//
////External Retreat Request Form Glen Waverley
//Route::post('/external_retreat_request_form_glen_waverley-request', [ExternalRetreatRequestFormGlenWaverleyController::class, 'store']);
//
////External Retreat Request Form Hallam
//Route::post('/external_retreat_request_form_hallam-request', [ExternalRetreatHallamController::class, 'store']);
//
////External Retreat Request Form Packenham
//Route::post('/external_retreat_request_form_packenham-request', [ExternalRetreatPackenhamController::class, 'store']);
//
////Future Plans Request Form
//Route::post('/future_plans_request_form-request', [FuturePlansRequestFormController::class, 'store']);
//
////Five-Year Request Form
//Route::post('/five_year-request', [FiveYearRequestController::class, 'store']);
//
////Gilan Pasa Request Form
//Route::post('/gilanpasa-request', [GilanPasaRequestController::class, 'store']);
//
//
//// Event routes
//Route::apiResource('events', EventController::class);
//Route::post('events/{id}/restore', [EventController::class, 'restore'])->name('events.restore');
//Route::get('events-list/with-trashed', [EventController::class, 'indexWithTrashed'])->name('events.with-trashed');
//
//
//// Get all forms
//Route::get('get-katina-ceremony-requests', [KatinaCeremonyRequestFormController::class, 'index']);
//
//// Create new form
//Route::post('create-katina-ceremony-requests', [KatinaCeremonyRequestFormController::class, 'store']);
//
//// Get single form
//Route::get('get-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'show']);
//
//// Update form
//Route::put('update-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'update']);
//
//// Delete form
//Route::delete('delete-katina-ceremony-requests/{id}', [KatinaCeremonyRequestFormController::class, 'destroy']);
//
//// Update status only
//Route::patch('katina-ceremony-requests/{id}/status', [KatinaCeremonyRequestFormController::class, 'updateStatus']);
//
//
//// Get all registrations
//Route::get('get-internal-retreat-organiser-registrations', [InternalRetreatOrganiserRegistrationController::class, 'index']);
//
//// Create new registration
//Route::post('create-internal-retreat-organiser-registrations', [InternalRetreatOrganiserRegistrationController::class, 'store']);
//
//// Get single registration
//Route::get('get-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'show']);
//
//// Update registration
//Route::put('update-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'update']);
//
//// Delete registration
//Route::delete('delete-internal-retreat-organiser-registrations/{id}', [InternalRetreatOrganiserRegistrationController::class, 'destroy']);
//
//// Update status only
//Route::patch('internal-retreat-organiser-registrations/{id}/status', [InternalRetreatOrganiserRegistrationController::class, 'updateStatus']);
//
//
////Route::apiResource('solo-retreat-registrations', SoloRetreatRegistrationController::class);
////Route::patch('solo-retreat-registrations/{id}/status', [SoloRetreatRegistrationController::class, 'updateStatus']);
////Route::get('solo-retreat-registrations/status/{status}', [SoloRetreatRegistrationController::class, 'getByStatus']);
//
//// Get all registrations
//Route::get('get-solo-retreat-registrations', [SoloRetreatRegistrationController::class, 'index']);
//
//// Create new registration
//Route::post('create-solo-retreat-registrations', [SoloRetreatRegistrationController::class, 'store']);
//
//// Get single registration
//Route::get('get-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'show']);
//
//// Update registration
//Route::put('update-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'update']);
//
//// Delete registration
//Route::delete('delete-solo-retreat-registrations/{id}', [SoloRetreatRegistrationController::class, 'destroy']);
//
//// Update status only
//Route::patch('solo-retreat-registrations/{id}/status', [SoloRetreatRegistrationController::class, 'updateStatus']);
//
//// Get by status
//Route::get('get-solo-retreat-registrations/status/{status}', [SoloRetreatRegistrationController::class, 'getByStatus']);
//
//
////Route::apiResource('solo-retreats', SoloRetreatController::class);
////Route::patch('solo-retreats/{id}/status', [SoloRetreatController::class, 'updateStatus']);
////Route::get('solo-retreats/status/{status}', [SoloRetreatController::class, 'getByStatus']);
////Route::get('solo-retreats/stats', [SoloRetreatController::class, 'getStats']);
//
//
//// Get all retreats
//Route::get('get-solo-retreats', [SoloRetreatController::class, 'index']);
//
//// Create new retreat
//Route::post('create-solo-retreats', [SoloRetreatController::class, 'store']);
//
//// Get single retreat
//Route::get('get-solo-retreats/{id}', [SoloRetreatController::class, 'show']);
//
//// Update retreat
//Route::put('update-solo-retreats/{id}', [SoloRetreatController::class, 'update']);
//
//// Delete retreat
//Route::delete('delete-solo-retreats/{id}', [SoloRetreatController::class, 'destroy']);
//
//// Update status only
//Route::patch('solo-retreats/{id}/status', [SoloRetreatController::class, 'updateStatus']);
//
//// Get by status
//Route::get('get-solo-retreats/status/{status}', [SoloRetreatController::class, 'getByStatus']);
//
//// Get stats
//Route::get('get-solo-retreats/stats', [SoloRetreatController::class, 'getStats']);
//
//
////Route::apiResource('guest-speaker-requests', GuestSpeakerRequestController::class);
////Route::patch('guest-speaker-requests/{id}/status', [GuestSpeakerRequestController::class, 'updateStatus']);
////Route::get('guest-speaker-requests/status/{status}', [GuestSpeakerRequestController::class, 'getByStatus']);
////Route::get('guest-speaker-requests/stats', [GuestSpeakerRequestController::class, 'getStats']);
//
//// Get all requests
//Route::get('get-guest-speaker-requests', [GuestSpeakerRequestController::class, 'index']);
//
//// Create new request
//Route::post('create-guest-speaker-requests', [GuestSpeakerRequestController::class, 'store']);
//
//// Get single request
//Route::get('get-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'show']);
//
//// Update request
//Route::put('update-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'update']);
//
//// Delete request
//Route::delete('delete-guest-speaker-requests/{id}', [GuestSpeakerRequestController::class, 'destroy']);
//
//// Update status only
//Route::patch('guest-speaker-requests/{id}/status', [GuestSpeakerRequestController::class, 'updateStatus']);
//
//// Get by status
//Route::get('get-guest-speaker-requests/status/{status}', [GuestSpeakerRequestController::class, 'getByStatus']);
//
//// Get stats
//Route::get('get-guest-speaker-requests/stats', [GuestSpeakerRequestController::class, 'getStats']);
//
//
//// Dhamma Talks
//Route::apiResource('dhamma-talks', DhammaTalkController::class);
//Route::get('dhamma-talks/search', [DhammaTalkController::class, 'search']);
