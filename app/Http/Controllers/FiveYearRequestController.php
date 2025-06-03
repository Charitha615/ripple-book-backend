<?php

namespace App\Http\Controllers;

use App\Models\FiveYearRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FiveYearRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'street_address_line_1' => 'required|string|max:255',
            'street_address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'wt_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            '5_land_plots' => 'boolean',
            '10_land_plots' => 'boolean',
            '20_land_plots' => 'boolean',
            '50_land_plots' => 'boolean',
            'query' => 'nullable|string|max:1000',
            'ip_address' => 'required|string|max:45',
        ]);

        $fiveYearRequest = FiveYearRequest::create($validatedData);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $fiveYearRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Five Year Request Form',
            'old_values' => null, // No old values for a new submission
            'new_values' => $fiveYearRequest,
            'description' => $validatedData['first_name'] . " submitted a Five Year Request Form. Mobile number is " . $validatedData['mobile_number'],
        ]);

        return response()->json(['message' => 'Form request submitted successfully', 'data' => $fiveYearRequest], 201);
    }

    public function getById($id)
    {
        try {
            $fiveYearRequest = FiveYearRequest::find($id);

            if (!$fiveYearRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Five year request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $fiveYearRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching five year request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch five year request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $fiveYearRequest = FiveYearRequest::find($id);

            if (!$fiveYearRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Five year request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $fiveYearRequest->getAttributes();

            // Validate the request data
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'nullable|date',
                'gender' => 'required|string|max:255',
                'street_address_line_1' => 'required|string|max:255',
                'street_address_line_2' => 'nullable|string|max:255',
                'city' => 'nullable|string|max:255',
                'postal_code' => 'nullable|string|max:255',
                'country' => 'nullable|string|max:255',
                'mobile_number' => 'required|string|max:255',
                'wt_number' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'five_land_plots' => 'sometimes|boolean',
                'ten_land_plots' => 'sometimes|boolean',
                'twenty_land_plots' => 'sometimes|boolean',
                'fifty_land_plots' => 'sometimes|boolean',
                'query' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the five year request
            $fiveYearRequest->update([
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'date_of_birth' => $request->input('date_of_birth'),
                'gender' => $request->input('gender'),
                'street_address_line_1' => $request->input('street_address_line_1'),
                'street_address_line_2' => $request->input('street_address_line_2'),
                'city' => $request->input('city'),
                'postal_code' => $request->input('postal_code'),
                'country' => $request->input('country'),
                'mobile_number' => $request->input('mobile_number'),
                'wt_number' => $request->input('wt_number'),
                'email' => $request->input('email'),
                '5_land_plots' => $request->input('five_land_plots', false),
                '10_land_plots' => $request->input('ten_land_plots', false),
                '20_land_plots' => $request->input('twenty_land_plots', false),
                '50_land_plots' => $request->input('fifty_land_plots', false),
                'query' => $request->input('query'), // Fixed: uses input() instead of ->query
            ]);

            // Create admin audit log (ensure this is also transactional)
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Five Year Request Form',
                'description' => "Updated five year request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($fiveYearRequest->refresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Five year request updated successfully',
                'data' => $fiveYearRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating five year request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update five year request',
                'error' => env('APP_DEBUG') ? $exception->getMessage() : null,
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $fiveYearRequests = FiveYearRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $fiveYearRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching five year requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch five year requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $fiveYearRequest = FiveYearRequest::find($id);

            if (!$fiveYearRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Five year request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $fiveYearRequest->getAttributes();

            // Soft delete the five year request
            $fiveYearRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Five Year Request Form',
                'description' => "Soft deleted five year request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Five year request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting five year request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete five year request'
            ], 500);
        }
    }
}
