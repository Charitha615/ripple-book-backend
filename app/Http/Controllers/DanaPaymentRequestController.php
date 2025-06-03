<?php

namespace App\Http\Controllers;

use App\Models\DanaPaymentRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DanaPaymentRequestController extends Controller
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
                'date' => 'nullable|string|max:255',
                'ip_address' => 'required|string|max:255',
            ]);

            $danaPaymentRequest = DanaPaymentRequest::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'dana_for_lunch' => $validatedData['dana_for_lunch'] ?? false,
                'dana_for_morning' => $validatedData['dana_for_morning'] ?? false,
                'dana_event_date' => $validatedData['date'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $danaPaymentRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Dana Payment Request',
                    'old_values' => null,
                    'new_values' => $danaPaymentRequest,
                    'description' => $validatedData['firstName'] . " submitted a Dana Payment Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for Dana Payment request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request submitted successfully',
                'data' => $danaPaymentRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in DanaPaymentRequest store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in DanaPaymentRequest store: ' . $exception->getMessage(), [
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
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $danaPaymentRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana Payment request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaPaymentRequest->getAttributes();

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_for_lunch' => 'boolean',
                'dana_for_morning' => 'boolean',
                'date' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the Dana Payment request
            $danaPaymentRequest->update([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'mobile_number' => $request->mobileNumber,
                'wt_number' => $request->wtNumber ?? null,
                'email' => $request->email,
                'dana_for_lunch' => $request->dana_for_lunch ?? false,
                'dana_for_morning' => $request->dana_for_morning ?? false,
                'dana_event_date' => $request->date ?? null,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Dana Payment Request',
                'description' => "Updated Dana Payment request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($danaPaymentRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request updated successfully',
                'data' => $danaPaymentRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Dana Payment request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $danaPaymentRequests = DanaPaymentRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $danaPaymentRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana Payment requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana Payment requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaPaymentRequest->getAttributes();

            // Soft delete the Dana Payment request
            $danaPaymentRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Dana Payment Request',
                'description' => "Soft deleted Dana Payment request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete Dana Payment request'
            ], 500);
        }
    }
}
