<?php

namespace App\Http\Controllers;

use App\Models\SermonRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class SermonRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'date' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'count' => 'nullable|string|max:255',
                'option' => 'nullable|string|max:255',
                'birthday' => 'boolean',
                'sevenday' => 'boolean',
                'warming' => 'boolean',
                'threemonths' => 'boolean',
                'oneyear' => 'boolean',
                'annually' => 'boolean',
                'weddings' => 'boolean',
                'ip_address' => 'required|string|max:255',
            ]);

            // Create the sermon request
            $sermonRequest = SermonRequest::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'date' => $validatedData['date'],
                'time' => $validatedData['time'],
                'count' => $validatedData['count'] ?? null,
                'option' => $validatedData['option'] ?? null,
                'birthday' => $validatedData['birthday'] ?? false,
                'sevenday' => $validatedData['sevenday'] ?? false,
                'warming' => $validatedData['warming'] ?? false,
                'threemonths' => $validatedData['threemonths'] ?? false,
                'oneyear' => $validatedData['oneyear'] ?? false,
                'annually' => $validatedData['annually'] ?? false,
                'weddings' => $validatedData['weddings'] ?? false,
                'ip_address' => $validatedData['ip_address'],
            ]);

            // Log the user activity
            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $sermonRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Damma Sermons Request',
                    'old_values' => null,
                    'new_values' => $sermonRequest,
                    'description' => $validatedData['firstName'] . " submitted a Damma Sermons Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                // Log the logging failure but don't fail the main request
                Log::error('Failed to create user log for sermon request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Sermon request submitted successfully',
                'data' => $sermonRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            // Return validation errors in consistent format
            $errors = $validationException->errors();
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            // Handle database query exceptions
            Log::error('Database error in SermonRequest store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            // Handle any other exceptions
            Log::error('Unexpected error in SermonRequest store: ' . $exception->getMessage(), [
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
            $sermonRequest = SermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sermon request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $sermonRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching sermon request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sermon request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $sermonRequest = SermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sermon request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $sermonRequest->getAttributes();

            // Validate the request data using snake_case keys
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'date' => 'required|string|max:255',
                'time' => 'required|string|max:255',
                'count' => 'nullable|string|max:255',
                'option' => 'nullable|string|max:255',
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

            // Update the sermon request with snake_case keys
            $sermonRequest->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'wt_number' => $request->wt_number ?? null,
                'email' => $request->email,
                'date' => $request->date,
                'time' => $request->time,
                'count' => $request->count ?? null,
                'option' => $request->option ?? null,
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
                'entity_area' => 'Damma Sermons Request',
                'description' => "Updated sermon request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($sermonRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sermon request updated successfully',
                'data' => $sermonRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating sermon request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update sermon request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $sermonRequests = SermonRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $sermonRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching sermon requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch sermon requests'
            ], 500);
        }
    }
    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $sermonRequest = SermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sermon request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $sermonRequest->getAttributes();

            // Soft delete the sermon request
            $sermonRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Damma Sermons Request',
                'description' => "Soft deleted sermon request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sermon request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting sermon request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete sermon request'
            ], 500);
        }
    }
}
