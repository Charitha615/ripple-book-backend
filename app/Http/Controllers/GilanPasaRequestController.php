<?php

namespace App\Http\Controllers;

use App\Models\GilanPasaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class GilanPasaRequestController extends Controller
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

            $gilanPasaRequest = GilanPasaRequest::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'date' => $validatedData['date'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $gilanPasaRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Gilan Pasa Request',
                    'old_values' => null,
                    'new_values' => $gilanPasaRequest,
                    'description' => $validatedData['firstName'] . " submitted a Gilan Pasa Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for Gilan Pasa request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Gilan Pasa request submitted successfully',
                'data' => $gilanPasaRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in Gilan Pasa request store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in Gilan Pasa request store: ' . $exception->getMessage(), [
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
            $gilanPasaRequest = GilanPasaRequest::find($id);

            if (!$gilanPasaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gilan Pasa request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $gilanPasaRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Gilan Pasa request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Gilan Pasa request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $gilanPasaRequest = GilanPasaRequest::find($id);

            if (!$gilanPasaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gilan Pasa request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $gilanPasaRequest->getAttributes();

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'date' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the Gilan Pasa request
            $gilanPasaRequest->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'wt_number' => $request->wt_number ?? null,
                'email' => $request->email,
                'date' => $request->date ?? null,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Gilan Pasa Request',
                'description' => "Updated Gilan Pasa request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($gilanPasaRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gilan Pasa request updated successfully',
                'data' => $gilanPasaRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating Gilan Pasa request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Gilan Pasa request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $gilanPasaRequests = GilanPasaRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $gilanPasaRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Gilan Pasa requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Gilan Pasa requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $gilanPasaRequest = GilanPasaRequest::find($id);

            if (!$gilanPasaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gilan Pasa request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $gilanPasaRequest->getAttributes();

            // Soft delete the Gilan Pasa request
            $gilanPasaRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Gilan Pasa Request',
                'description' => "Soft deleted Gilan Pasa request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Gilan Pasa request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting Gilan Pasa request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete Gilan Pasa request'
            ], 500);
        }
    }



    public function updateStatus(Request $request, $id)
    {
        try {
            $Request = GilanPasaRequest::find($id);

            if (!$Request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gilan Pasa Request request not found'
                ], 404);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $status = $request->input('status');
            $reason = $request->input('status_reason');

            // Require reason for Approved, Rejected, On hold
            if (in_array($status, ['Approved', 'Rejected', 'On hold']) && empty($reason)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status reason is required when status is Approved, Rejected, or On hold'
                ], 422);
            }

            // Capture old values before updating
            $oldValues = $Request->getAttributes();

            // Update the status
            $Request->update([
                'status' => $status,
                'status_reason' => $reason
            ]);

            // Capture new values after updating
            $newValues = $Request->fresh()->getAttributes();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Status Change',
                'entity_area' => 'Gilan Pasa Request',
                'description' => "Changed status of Gilan Pasa request ID: {$id} to '{$status}'",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($newValues),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $Request
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating Gilan Pasa request status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => 'Please try again later'
            ], 500);
        }
    }
}
