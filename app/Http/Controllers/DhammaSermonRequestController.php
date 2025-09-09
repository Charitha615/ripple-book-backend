<?php

namespace App\Http\Controllers;

use App\Models\DhammaSermonRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Auth;

class DhammaSermonRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = DhammaSermonRequest::orderBy('created_at', 'desc')->get();
        return view('dhamma-sermon-requests.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dhamma-sermon-requests.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email',
            'date' => 'required|date',
            'time' => 'required',
            'sanga_members_count' => 'required|integer|min:0',
            'seven_day' => 'sometimes|boolean',
            'three_day' => 'sometimes|boolean',
            'one_year' => 'sometimes|boolean',
            'annually' => 'sometimes|boolean',
            'birthday' => 'sometimes|boolean',
            'house_warming' => 'sometimes|boolean',
            'weddings_anniversary' => 'sometimes|boolean',
            'other_event' => 'nullable|string|max:255',
        ]);

        // Add IP address to the request
        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'Pending'; // Default status

        // Create the sermon request
        $sermonRequest = DhammaSermonRequest::create($validated);

        // Create user log entry
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $sermonRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Dhamma Sermon Request',
            'old_values' => null,
            'new_values' => $sermonRequest,
            'description' => $validated['first_name'] . ' ' . $validated['last_name'] .
                " submitted a Dhamma Sermon Request. Mobile number is " .
                $validated['mobile_number'],
        ]);

        return redirect()->route('dhamma-sermon-requests.index')
            ->with('success', 'Dhamma Sermon request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(DhammaSermonRequest $dhammaSermonRequest)
    {
        return view('dhamma-sermon-requests.show', compact('dhammaSermonRequest'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DhammaSermonRequest $dhammaSermonRequest)
    {
        return view('dhamma-sermon-requests.edit', compact('dhammaSermonRequest'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DhammaSermonRequest $dhammaSermonRequest)
    {
        $oldValues = $dhammaSermonRequest->toArray();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email',
            'date' => 'required|date',
            'time' => 'required',
            'sanga_members_count' => 'required|integer|min:0',
            'seven_day' => 'sometimes|boolean',
            'three_day' => 'sometimes|boolean',
            'one_year' => 'sometimes|boolean',
            'annually' => 'sometimes|boolean',
            'birthday' => 'sometimes|boolean',
            'house_warming' => 'sometimes|boolean',
            'weddings_anniversary' => 'sometimes|boolean',
            'other_event' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string',
        ]);

        $dhammaSermonRequest->update($validated);

        // Create user log entry for update
        UserLogController::createLog([
            'user_id' => auth()->id(),
            'form_id' => $dhammaSermonRequest->id,
            'action_type' => 'form_update',
            'entity_area' => 'Dhamma Sermon Request',
            'old_values' => $oldValues,
            'new_values' => $dhammaSermonRequest->fresh()->toArray(),
            'description' => 'Dhamma Sermon Request updated. Status changed to: ' . $validated['status'],
        ]);

        return redirect()->route('dhamma-sermon-requests.index')
            ->with('success', 'Dhamma Sermon request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DhammaSermonRequest $dhammaSermonRequest)
    {
        $oldValues = $dhammaSermonRequest->toArray();
        $id = $dhammaSermonRequest->id;

        $dhammaSermonRequest->delete();

        // Create user log entry for deletion
        UserLogController::createLog([
            'user_id' => auth()->id(),
            'form_id' => $id,
            'action_type' => 'form_deletion',
            'entity_area' => 'Dhamma Sermon Request',
            'old_values' => $oldValues,
            'new_values' => null,
            'description' => 'Dhamma Sermon Request deleted',
        ]);

        return redirect()->route('dhamma-sermon-requests.index')
            ->with('success', 'Dhamma Sermon request deleted successfully.');
    }

    /**
     * Update the status of a specific request.
     */
    public function updateStatus(Request $request, $id)
    {
        $sermonRequest = DhammaSermonRequest::findOrFail($id);
        $oldValues = $sermonRequest->toArray();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string',
        ]);

        $sermonRequest->update($validated);

        // Create user log entry for status update
        UserLogController::createLog([
            'user_id' => auth()->id(),
            'form_id' => $sermonRequest->id,
            'action_type' => 'status_update',
            'entity_area' => 'Dhamma Sermon Request',
            'old_values' => $oldValues,
            'new_values' => $sermonRequest->fresh()->toArray(),
            'description' => 'Status changed to: ' . $validated['status'] .
                ($validated['status_reason'] ? '. Reason: ' . $validated['status_reason'] : ''),
        ]);

        return redirect()->route('dhamma-sermon-requests.index')
            ->with('success', 'Status updated successfully.');
    }

    // ==================== API FUNCTIONS ====================

    /**
     * API: Get all dhamma sermon requests
     */
    public function apiIndex()
    {
        try {
            $requests = DhammaSermonRequest::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $requests,
                'message' => 'Requests retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve requests: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Get a specific dhamma sermon request
     */
    public function apiShow($id)
    {
        try {
            $request = DhammaSermonRequest::find($id);

            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $request,
                'message' => 'Request retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Create a new dhamma sermon request
     */
    public function apiStore(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:20',
                'whatsapp_number' => 'nullable|string|max:20',
                'email_address' => 'required|email',
                'date' => 'required|date',
                'time' => 'required',
                'sanga_members_count' => 'required|integer|min:0',
                'seven_day' => 'sometimes|boolean',
                'three_day' => 'sometimes|boolean',
                'one_year' => 'sometimes|boolean',
                'annually' => 'sometimes|boolean',
                'birthday' => 'sometimes|boolean',
                'house_warming' => 'sometimes|boolean',
                'weddings_anniversary' => 'sometimes|boolean',
                'other_event' => 'nullable|string|max:255',
            ]);

            // Add IP address to the request
            $validated['ip_address'] = $request->ip();
            $validated['status'] = 'Pending'; // Default status

            // Create the sermon request
            $sermonRequest = DhammaSermonRequest::create($validated);

            // Create user log entry
            UserLogController::createLog([
                'user_id' => null,
                'form_id' => $sermonRequest->id,
                'action_type' => 'form_submission',
                'entity_area' => 'Dhamma Sermon Request',
                'old_values' => null,
                'new_values' => $sermonRequest,
                'description' => $validated['first_name'] . ' ' . $validated['last_name'] .
                    " submitted a Dhamma Sermon Request via API. Mobile number is " .
                    $validated['mobile_number'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $sermonRequest,
                'message' => 'Dhamma Sermon request submitted successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update a dhamma sermon request
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $sermonRequest = DhammaSermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $sermonRequest->toArray();

            $validated = $request->validate([
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'mobile_number' => 'sometimes|required|string|max:20',
                'whatsapp_number' => 'nullable|string|max:20',
                'email_address' => 'sometimes|required|email',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required',
                'sanga_members_count' => 'sometimes|required|integer|min:0',
                'seven_day' => 'sometimes|boolean',
                'three_day' => 'sometimes|boolean',
                'one_year' => 'sometimes|boolean',
                'annually' => 'sometimes|boolean',
                'birthday' => 'sometimes|boolean',
                'house_warming' => 'sometimes|boolean',
                'weddings_anniversary' => 'sometimes|boolean',
                'other_event' => 'nullable|string|max:255',
                'status' => 'sometimes|required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string',
            ]);

            $sermonRequest->update($validated);

            // Create user log entry for update
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'form_update',
                'entity_area' => 'Dhamma Sermon Request',
                'old_values' => $oldValues,
                'new_values' => $sermonRequest->fresh()->toArray(),
                'description' => 'Dhamma Sermon Request updated via API',
            ]);

            return response()->json([
                'success' => true,
                'data' => $sermonRequest->fresh(),
                'message' => 'Request updated successfully'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Delete a dhamma sermon request
     */
    public function apiDestroy($id)
    {
        try {
            $sermonRequest = DhammaSermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $sermonRequest->toArray();

            $sermonRequest->delete();

            // Create user log entry for deletion
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'form_deletion',
                'entity_area' => 'Dhamma Sermon Request',
                'old_values' => $oldValues,
                'new_values' => null,
                'description' => 'Dhamma Sermon Request deleted via API',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Request deleted successfully'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * API: Update status of a dhamma sermon request
     */
    public function apiUpdateStatus(Request $request, $id)
    {
        try {
            $sermonRequest = DhammaSermonRequest::find($id);

            if (!$sermonRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $sermonRequest->toArray();

            $validated = $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string',
            ]);

            $sermonRequest->update($validated);

            // Create user log entry for status update
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'status_update',
                'entity_area' => 'Dhamma Sermon Request',
                'old_values' => $oldValues,
                'new_values' => $sermonRequest->fresh()->toArray(),
                'description' => 'Status changed to: ' . $validated['status'] .
                    ($validated['status_reason'] ? '. Reason: ' . $validated['status_reason'] : ''),
            ]);

            return response()->json([
                'success' => true,
                'data' => $sermonRequest->fresh(),
                'message' => 'Status updated successfully'
            ], 200);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }
}
