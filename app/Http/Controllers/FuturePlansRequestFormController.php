<?php

namespace App\Http\Controllers;

use App\Models\FuturePlansRequestForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FuturePlansRequestFormController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:255',
                'mobile_number' => 'nullable|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'contribute' => 'required|string|max:255',
                'inquire' => 'nullable|string|max:255',
                'ip_address' => 'required|string|max:255',
            ]);

            $futurePlansRequest = FuturePlansRequestForm::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'address' => $validatedData['address'],
                'city' => $validatedData['city'] ?? null,
                'postal_code' => $validatedData['postal_code'] ?? null,
                'mobile_number' => $validatedData['mobile_number'] ?? null,
                'wt_number' => $validatedData['wt_number'] ?? null,
                'email' => $validatedData['email'] ?? null,
                'contribute' => $validatedData['contribute'],
                'inquire' => $validatedData['inquire'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $futurePlansRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Future Plans Request Form',
                    'old_values' => null,
                    'new_values' => $futurePlansRequest,
                    'description' => $validatedData['firstName'] . " submitted a Future Plans Request Form. Mobile number is " . ($validatedData['mobile_number'] ?? 'not provided'),
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for future plans request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Future plans request submitted successfully',
                'data' => $futurePlansRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in future plans request store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in future plans request store: ' . $exception->getMessage(), [
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
            $futurePlansRequest = FuturePlansRequestForm::find($id);

            if (!$futurePlansRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Future plans request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $futurePlansRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching future plans request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch future plans request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $futurePlansRequest = FuturePlansRequestForm::find($id);

            if (!$futurePlansRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Future plans request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $futurePlansRequest->getAttributes();

            // Validate the request data with consistent snake_case
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'address' => 'required|string|max:255',
                'city' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:255',
                'mobile_number' => 'nullable|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'contribute' => 'required|string|max:255',
                'inquire' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update with consistent field names
            $futurePlansRequest->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'address' => $request->address,
                'city' => $request->city ?? null,
                'postal_code' => $request->postal_code ?? null,
                'mobile_number' => $request->mobile_number ?? null,
                'wt_number' => $request->wt_number ?? null,
                'email' => $request->email ?? null,
                'contribute' => $request->contribute,
                'inquire' => $request->inquire ?? null,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Future Plans Request Form',
                'description' => "Updated future plans request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($futurePlansRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Future plans request updated successfully',
                'data' => $futurePlansRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating future plans request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update future plans request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $futurePlansRequests = FuturePlansRequestForm::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $futurePlansRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching future plans requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch future plans requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $futurePlansRequest = FuturePlansRequestForm::find($id);

            if (!$futurePlansRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Future plans request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $futurePlansRequest->getAttributes();

            // Soft delete the future plans request
            $futurePlansRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Future Plans Request Form',
                'description' => "Soft deleted future plans request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Future plans request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting future plans request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete future plans request'
            ], 500);
        }
    }
}
