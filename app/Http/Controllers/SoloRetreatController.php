<?php

namespace App\Http\Controllers;

use App\Models\SoloRetreat;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SoloRetreatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $retreats = SoloRetreat::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $retreats
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching solo retreats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch retreat registrations'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:18',
            'email_address' => 'required|email|unique:solo_retreats,email_address',
            'mobile_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'number_of_days' => 'nullable|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['ip_address'] = $request->ip();

            $retreat = SoloRetreat::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Solo retreat registration submitted successfully',
                'data' => $retreat
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating solo retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create registration'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $retreat = SoloRetreat::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $retreat
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching solo retreat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $retreat = SoloRetreat::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'date_of_birth' => 'sometimes|required|date',
                'age' => 'sometimes|required|integer|min:18',
                'email_address' => 'sometimes|required|email|unique:solo_retreats,email_address,' . $id,
                'number_of_days' => 'nullable|integer|min:1',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $retreat->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => $retreat
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating solo retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update registration'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $retreat = SoloRetreat::findOrFail($id);
            $retreat->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting solo retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete registration'
            ], 500);
        }
    }

    /**
     * Update registration status
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $retreat = SoloRetreat::findOrFail($id);
            $retreat->update([
                'status' => $request->status,
                'status_reason' => $request->status_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $retreat
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating registration status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Get registrations by status
     */
    public function getByStatus(string $status): JsonResponse
    {
        $validStatuses = ['Pending', 'Approved', 'Rejected', 'On hold'];

        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status'
            ], 422);
        }

        try {
            $retreats = SoloRetreat::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $retreats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching registrations by status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch registrations'
            ], 500);
        }
    }

    /**
     * Get statistics
     */
    public function getStats(): JsonResponse
    {
        try {
            $stats = [
                'total' => SoloRetreat::count(),
                'pending' => SoloRetreat::pending()->count(),
                'approved' => SoloRetreat::approved()->count(),
                'rejected' => SoloRetreat::rejected()->count(),
                'on_hold' => SoloRetreat::onHold()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }
}
