<?php

namespace App\Http\Controllers;

use App\Models\DanaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DanaRequestController extends Controller
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
                'date' => 'nullable|string|max:255',
                'ip_address' => 'required|string|max:255',
            ]);

            $danaRequest = DanaRequest::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'dana_event_date' => $validatedData['date'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $danaRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Dana Request',
                    'old_values' => null,
                    'new_values' => $danaRequest,
                    'description' => $validatedData['firstName'] . " submitted a Dana Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for Dana request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dana request submitted successfully',
                'data' => $danaRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in DanaRequest store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in DanaRequest store: ' . $exception->getMessage(), [
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
            $danaRequest = DanaRequest::find($id);

            if (!$danaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $danaRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $danaRequest = DanaRequest::find($id);

            if (!$danaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaRequest->getAttributes();

            // Validate the request data using snake_case keys
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_event_date' => 'nullable|string|max:255', // Changed from 'date' to match DB column
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the Dana request with consistent field names
            $danaRequest->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'wt_number' => $request->wt_number ?? null,
                'email' => $request->email,
                'dana_event_date' => $request->dana_event_date ?? null,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Dana Request',
                'description' => "Updated Dana request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($danaRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana request updated successfully',
                'data' => $danaRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating Dana request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Dana request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $danaRequests = DanaRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $danaRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $danaRequest = DanaRequest::find($id);

            if (!$danaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaRequest->getAttributes();

            // Soft delete the Dana request
            $danaRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Dana Request',
                'description' => "Soft deleted Dana request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting Dana request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete Dana request'
            ], 500);
        }
    }
}
