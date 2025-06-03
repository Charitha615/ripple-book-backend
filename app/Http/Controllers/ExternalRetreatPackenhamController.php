<?php

namespace App\Http\Controllers;

use App\Models\ExternalRetreatPackenham;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ExternalRetreatPackenhamController extends Controller
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
                'number_of_people' => 'nullable|integer',
                'ip_address' => 'required|string|max:255',
            ]);

            $externalRetreatPackenham = ExternalRetreatPackenham::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'number_of_people' => $validatedData['number_of_people'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $externalRetreatPackenham->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'External Retreat Request Form Packenham',
                    'old_values' => null,
                    'new_values' => $externalRetreatPackenham,
                    'description' => $validatedData['firstName'] . " submitted an External Retreat Request Form (Packenham). Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for external retreat request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'External retreat request submitted successfully',
                'data' => $externalRetreatPackenham
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in external retreat request store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in external retreat request store: ' . $exception->getMessage(), [
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
            $externalRetreatPackenham = ExternalRetreatPackenham::find($id);

            if (!$externalRetreatPackenham) {
                return response()->json([
                    'success' => false,
                    'message' => 'External retreat request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $externalRetreatPackenham
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching external retreat request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch external retreat request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $externalRetreatPackenham = ExternalRetreatPackenham::find($id);

            if (!$externalRetreatPackenham) {
                return response()->json([
                    'success' => false,
                    'message' => 'External retreat request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $externalRetreatPackenham->getAttributes();

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'number_of_people' => 'nullable|integer',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the external retreat request
            $externalRetreatPackenham->update([
                'first_name' => $request->firstName,
                'last_name' => $request->lastName,
                'mobile_number' => $request->mobileNumber,
                'wt_number' => $request->wtNumber ?? null,
                'email' => $request->email,
                'number_of_people' => $request->number_of_people ?? null,
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'External Retreat Request Form Packenham',
                'description' => "Updated external retreat request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($externalRetreatPackenham->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'External retreat request updated successfully',
                'data' => $externalRetreatPackenham
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating external retreat request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update external retreat request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $externalRetreatPackenhamRequests = ExternalRetreatPackenham::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $externalRetreatPackenhamRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching external retreat requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch external retreat requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $externalRetreatPackenham = ExternalRetreatPackenham::find($id);

            if (!$externalRetreatPackenham) {
                return response()->json([
                    'success' => false,
                    'message' => 'External retreat request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $externalRetreatPackenham->getAttributes();

            // Soft delete the external retreat request
            $externalRetreatPackenham->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'External Retreat Request Form Packenham',
                'description' => "Soft deleted external retreat request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'External retreat request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting external retreat request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete external retreat request'
            ], 500);
        }
    }
}
