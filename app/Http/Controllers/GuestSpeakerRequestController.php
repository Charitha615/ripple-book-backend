<?php

namespace App\Http\Controllers;

use App\Models\GuestSpeakerRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class GuestSpeakerRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $requests = GuestSpeakerRequest::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching guest speaker requests: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch guest speaker requests'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'organiser_full_name' => 'required|string|max:255',
            'organiser_email_address' => 'required|email',
            'guest_full_name' => 'required|string|max:255',
            'guest_first_name' => 'nullable|string|max:255',
            'guest_last_name' => 'nullable|string|max:255',
            'guest_email_address' => 'nullable|email',
            'organiser_mobile_number' => 'nullable|string|max:20',
            'organiser_whatsapp_number' => 'nullable|string|max:20',
            'guest_date_of_birth' => 'nullable|date',
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

            $guestRequest = GuestSpeakerRequest::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Guest speaker request submitted successfully',
                'data' => $guestRequest
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating guest speaker request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create guest speaker request'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $guestRequest = GuestSpeakerRequest::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $guestRequest
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching guest speaker request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Guest speaker request not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $guestRequest = GuestSpeakerRequest::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'organiser_full_name' => 'sometimes|required|string|max:255',
                'organiser_email_address' => 'sometimes|required|email',
                'guest_full_name' => 'sometimes|required|string|max:255',
                'guest_email_address' => 'nullable|email',
                'organiser_mobile_number' => 'nullable|string|max:20',
                'organiser_whatsapp_number' => 'nullable|string|max:20',
                'guest_date_of_birth' => 'nullable|date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $guestRequest->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Guest speaker request updated successfully',
                'data' => $guestRequest
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating guest speaker request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update guest speaker request'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $guestRequest = GuestSpeakerRequest::findOrFail($id);
            $guestRequest->delete();

            return response()->json([
                'success' => true,
                'message' => 'Guest speaker request deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting guest speaker request: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete guest speaker request'
            ], 500);
        }
    }

    /**
     * Update request status
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
            $guestRequest = GuestSpeakerRequest::findOrFail($id);
            $guestRequest->update([
                'status' => $request->status,
                'status_reason' => $request->status_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $guestRequest
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating guest speaker request status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Get requests by status
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
            $requests = GuestSpeakerRequest::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $requests
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching guest speaker requests by status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch requests'
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
                'total' => GuestSpeakerRequest::count(),
                'pending' => GuestSpeakerRequest::pending()->count(),
                'approved' => GuestSpeakerRequest::approved()->count(),
                'rejected' => GuestSpeakerRequest::rejected()->count(),
                'on_hold' => GuestSpeakerRequest::onHold()->count(),
            ];

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching guest speaker request statistics: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch statistics'
            ], 500);
        }
    }
}
