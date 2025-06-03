<?php

namespace App\Http\Controllers;

use App\Models\DanaAtHome;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DanaAtHomeController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_for_lunch' => 'boolean',
                'dana_for_morning' => 'boolean',
                'specific_event' => 'nullable|string|max:255',
                'other' => 'nullable|string|max:255',
                'birthday' => 'boolean',
                'sevenday' => 'boolean',
                'warming' => 'boolean',
                'threemonths' => 'boolean',
                'oneyear' => 'boolean',
                'annually' => 'boolean',
                'weddings' => 'boolean',
                'ip_address' => 'required|string|max:255',
            ]);

            $danaAtHomeRequest = DanaAtHome::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'dana_for_lunch' => $validatedData['dana_for_lunch'] ?? false,
                'dana_for_morning' => $validatedData['dana_for_morning'] ?? false,
                'specific_event' => $validatedData['specific_event'] ?? null,
                'other' => $validatedData['other'] ?? null,
                'birthday' => $validatedData['birthday'] ?? false,
                'sevenday' => $validatedData['sevenday'] ?? false,
                'warming' => $validatedData['warming'] ?? false,
                'threemonths' => $validatedData['threemonths'] ?? false,
                'oneyear' => $validatedData['oneyear'] ?? false,
                'annually' => $validatedData['annually'] ?? false,
                'weddings' => $validatedData['weddings'] ?? false,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $danaAtHomeRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Dana At Home Request',
                    'old_values' => null,
                    'new_values' => $danaAtHomeRequest,
                    'description' => $validatedData['firstName'] . " submitted a Dana At Home Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for Dana At Home request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dana At Home request submitted successfully',
                'data' => $danaAtHomeRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in DanaAtHome store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in DanaAtHome store: ' . $exception->getMessage(), [
                'exception' => $exception,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => 'Please try again later'
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $danaAtHomeRequest = DanaAtHome::find($id);

            if (!$danaAtHomeRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana At Home request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $danaAtHomeRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana At Home request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana At Home request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $danaAtHomeRequest = DanaAtHome::find($id);

            if (!$danaAtHomeRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana At Home request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaAtHomeRequest->getAttributes();

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_for_lunch' => 'boolean',
                'dana_for_morning' => 'boolean',
                'specific_event' => 'nullable|string|max:255',
                'other' => 'nullable|string|max:255',
                'birthday' => 'boolean',
                'sevenday' => 'boolean',
                'warming' => 'boolean',
                'threemonths' => 'boolean',
                'oneyear' => 'boolean',
                'annually' => 'boolean',
                'weddings' => 'boolean',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the Dana At Home request
            $danaAtHomeRequest->update([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'mobile_number' => $request->mobileNumber,
                'wt_number' => $request->wtNumber ?? null,
                'email' => $request->email,
                'dana_for_lunch' => $request->dana_for_lunch ?? false,
                'dana_for_morning' => $request->dana_for_morning ?? false,
                'specific_event' => $request->specific_event ?? null,
                'other' => $request->other ?? null,
                'birthday' => $request->birthday ?? false,
                'sevenday' => $request->sevenday ?? false,
                'warming' => $request->warming ?? false,
                'threemonths' => $request->threemonths ?? false,
                'oneyear' => $request->oneyear ?? false,
                'annually' => $request->annually ?? false,
                'weddings' => $request->weddings ?? false,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Dana At Home Request',
                'description' => "Updated Dana At Home request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($danaAtHomeRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana At Home request updated successfully',
                'data' => $danaAtHomeRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating Dana At Home request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Dana At Home request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $danaAtHomeRequests = DanaAtHome::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $danaAtHomeRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana At Home requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana At Home requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $danaAtHomeRequest = DanaAtHome::find($id);

            if (!$danaAtHomeRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana At Home request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaAtHomeRequest->getAttributes();

            // Soft delete the Dana At Home request
            $danaAtHomeRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Dana At Home Request',
                'description' => "Soft deleted Dana At Home request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana At Home request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting Dana At Home request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete Dana At Home request'
            ], 500);
        }
    }
}
