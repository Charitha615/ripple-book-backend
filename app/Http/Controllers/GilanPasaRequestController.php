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
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
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
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'mobile_number' => $request->mobileNumber,
                'wt_number' => $request->wtNumber ?? null,
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
}
