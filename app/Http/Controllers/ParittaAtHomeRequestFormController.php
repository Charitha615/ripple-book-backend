<?php

namespace App\Http\Controllers;

use App\Models\Paritta_at_Home_Request_Form;
use Illuminate\Http\Request;
use App\Http\Controllers\UserLogController;
use Illuminate\Support\Facades\Auth;

class ParittaAtHomeRequestFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = Paritta_at_Home_Request_Form::orderBy('created_at', 'desc')->get();
        return view('paritta-at-home-request-forms.index', compact('requests'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('paritta-at-home-request-forms.create');
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
            'birthday' => 'sometimes|boolean',
            'new_business' => 'sometimes|boolean',
            'house_warming' => 'sometimes|boolean',
            'sick_in_need' => 'sometimes|boolean',
            'exams' => 'sometimes|boolean',
            'wedding_anniversary' => 'sometimes|boolean',
            'weddings' => 'sometimes|boolean',
            'pregnant_mums' => 'sometimes|boolean',
            'new_born' => 'sometimes|boolean',
            'other_event' => 'nullable|string|max:255',
        ]);

        // Add IP address to the request
        $validated['ip_address'] = $request->ip();
        $validated['status'] = 'Pending'; // Default status

        // Create the home request
        $parittaRequest = Paritta_at_Home_Request_Form::create($validated);

        // Create user log entry
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $parittaRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Paritta at Home Request',
            'old_values' => null,
            'new_values' => $parittaRequest,
            'description' => $validated['first_name'] . ' ' . $validated['last_name'] .
                " submitted a Paritta at Home Request. Mobile number is " .
                $validated['mobile_number'],
        ]);

        return redirect()->route('paritta-at-home-request-forms.index')
            ->with('success', 'Paritta at Home request submitted successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Paritta_at_Home_Request_Form $parittaAtHomeRequestForm)
    {
        return view('paritta-at-home-request-forms.show', compact('parittaAtHomeRequestForm'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Paritta_at_Home_Request_Form $parittaAtHomeRequestForm)
    {
        return view('paritta-at-home-request-forms.edit', compact('parittaAtHomeRequestForm'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Paritta_at_Home_Request_Form $parittaAtHomeRequestForm)
    {
        $oldValues = $parittaAtHomeRequestForm->toArray();

        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email',
            'date' => 'required|date',
            'time' => 'required',
            'sanga_members_count' => 'required|integer|min:0',
            'birthday' => 'sometimes|boolean',
            'new_business' => 'sometimes|boolean',
            'house_warming' => 'sometimes|boolean',
            'sick_in_need' => 'sometimes|boolean',
            'exams' => 'sometimes|boolean',
            'wedding_anniversary' => 'sometimes|boolean',
            'weddings' => 'sometimes|boolean',
            'pregnant_mums' => 'sometimes|boolean',
            'new_born' => 'sometimes|boolean',
            'other_event' => 'nullable|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string',
        ]);

        $parittaAtHomeRequestForm->update($validated);

        // Create user log entry for update
        UserLogController::createLog([
            'user_id' => auth()->id(), // Assuming an admin is making the update
            'form_id' => $parittaAtHomeRequestForm->id,
            'action_type' => 'form_update',
            'entity_area' => 'Paritta at Home Request',
            'old_values' => $oldValues,
            'new_values' => $parittaAtHomeRequestForm->fresh()->toArray(),
            'description' => 'Paritta at Home Request updated. Status changed to: ' . $validated['status'],
        ]);

        return redirect()->route('paritta-at-home-request-forms.index')
            ->with('success', 'Paritta at Home request updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Paritta_at_Home_Request_Form $parittaAtHomeRequestForm)
    {
        $oldValues = $parittaAtHomeRequestForm->toArray();
        $id = $parittaAtHomeRequestForm->id;

        $parittaAtHomeRequestForm->delete();

        // Create user log entry for deletion
        UserLogController::createLog([
            'user_id' => auth()->id(), // Assuming an admin is making the deletion
            'form_id' => $id,
            'action_type' => 'form_deletion',
            'entity_area' => 'Paritta at Home Request',
            'old_values' => $oldValues,
            'new_values' => null,
            'description' => 'Paritta at Home Request deleted',
        ]);

        return redirect()->route('paritta-at-home-request-forms.index')
            ->with('success', 'Paritta at Home request deleted successfully.');
    }

    /**
     * Update the status of a specific request.
     */
    public function updateStatus(Request $request, $id)
    {
        $parittaRequest = Paritta_at_Home_Request_Form::findOrFail($id);
        $oldValues = $parittaRequest->toArray();

        $validated = $request->validate([
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string',
        ]);

        $parittaRequest->update($validated);

        // Create user log entry for status update
        UserLogController::createLog([
            'user_id' => auth()->id(),
            'form_id' => $parittaRequest->id,
            'action_type' => 'status_update',
            'entity_area' => 'Paritta at Home Request',
            'old_values' => $oldValues,
            'new_values' => $parittaRequest->fresh()->toArray(),
            'description' => 'Status changed to: ' . $validated['status'] .
                ($validated['status_reason'] ? '. Reason: ' . $validated['status_reason'] : ''),
        ]);

        return redirect()->route('paritta-at-home-request-forms.index')
            ->with('success', 'Status updated successfully.');
    }

    // ==================== API FUNCTIONS ====================

    /**
     * API: Get all paritta at home requests
     */
    public function apiIndex()
    {
        try {
            $requests = Paritta_at_Home_Request_Form::orderBy('created_at', 'desc')->get();

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
     * API: Get a specific paritta at home request
     */
    public function apiShow($id)
    {
        try {
            $request = Paritta_at_Home_Request_Form::find($id);

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
     * API: Create a new paritta at home request
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
                'birthday' => 'sometimes|boolean',
                'new_business' => 'sometimes|boolean',
                'house_warming' => 'sometimes|boolean',
                'sick_in_need' => 'sometimes|boolean',
                'exams' => 'sometimes|boolean',
                'wedding_anniversary' => 'sometimes|boolean',
                'weddings' => 'sometimes|boolean',
                'pregnant_mums' => 'sometimes|boolean',
                'new_born' => 'sometimes|boolean',
                'other_event' => 'nullable|string|max:255',
            ]);

            // Add IP address to the request
            $validated['ip_address'] = $request->ip();
            $validated['status'] = 'Pending'; // Default status

            // Create the home request
            $parittaRequest = Paritta_at_Home_Request_Form::create($validated);

            // Create user log entry
            UserLogController::createLog([
                'user_id' => null,
                'form_id' => $parittaRequest->id,
                'action_type' => 'form_submission',
                'entity_area' => 'Paritta at Home Request',
                'old_values' => null,
                'new_values' => $parittaRequest,
                'description' => $validated['first_name'] . ' ' . $validated['last_name'] .
                    " submitted a Paritta at Home Request via API. Mobile number is " .
                    $validated['mobile_number'],
            ]);

            return response()->json([
                'success' => true,
                'data' => $parittaRequest,
                'message' => 'Paritta at Home request submitted successfully'
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
     * API: Update a paritta at home request
     */
    public function apiUpdate(Request $request, $id)
    {
        try {
            $parittaRequest = Paritta_at_Home_Request_Form::find($id);

            if (!$parittaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $parittaRequest->toArray();

            $validated = $request->validate([
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'mobile_number' => 'sometimes|required|string|max:20',
                'whatsapp_number' => 'nullable|string|max:20',
                'email_address' => 'sometimes|required|email',
                'date' => 'sometimes|required|date',
                'time' => 'sometimes|required',
                'sanga_members_count' => 'sometimes|required|integer|min:0',
                'birthday' => 'sometimes|boolean',
                'new_business' => 'sometimes|boolean',
                'house_warming' => 'sometimes|boolean',
                'sick_in_need' => 'sometimes|boolean',
                'exams' => 'sometimes|boolean',
                'wedding_anniversary' => 'sometimes|boolean',
                'weddings' => 'sometimes|boolean',
                'pregnant_mums' => 'sometimes|boolean',
                'new_born' => 'sometimes|boolean',
                'other_event' => 'nullable|string|max:255',
                'status' => 'sometimes|required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string',
            ]);

            $parittaRequest->update($validated);

            // Create user log entry for update
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'form_update',
                'entity_area' => 'Paritta at Home Request',
                'old_values' => $oldValues,
                'new_values' => $parittaRequest->fresh()->toArray(),
                'description' => 'Paritta at Home Request updated via API',
            ]);

            return response()->json([
                'success' => true,
                'data' => $parittaRequest->fresh(),
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
     * API: Delete a paritta at home request
     */
    public function apiDestroy($id)
    {
        try {
            $parittaRequest = Paritta_at_Home_Request_Form::find($id);

            if (!$parittaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $parittaRequest->toArray();

            $parittaRequest->delete();

            // Create user log entry for deletion
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'form_deletion',
                'entity_area' => 'Paritta at Home Request',
                'old_values' => $oldValues,
                'new_values' => null,
                'description' => 'Paritta at Home Request deleted via API',
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
     * API: Update status of a paritta at home request
     */
    public function apiUpdateStatus(Request $request, $id)
    {
        try {
            $parittaRequest = Paritta_at_Home_Request_Form::find($id);

            if (!$parittaRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Request not found'
                ], 404);
            }

            $oldValues = $parittaRequest->toArray();

            $validated = $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string',
            ]);

            $parittaRequest->update($validated);

            // Create user log entry for status update
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'form_id' => $parittaRequest->id,
                'action_type' => 'status_update',
                'entity_area' => 'Paritta at Home Request',
                'old_values' => $oldValues,
                'new_values' => $parittaRequest->fresh()->toArray(),
                'description' => 'Status changed to: ' . $validated['status'] .
                    ($validated['status_reason'] ? '. Reason: ' . $validated['status_reason'] : ''),
            ]);

            return response()->json([
                'success' => true,
                'data' => $parittaRequest->fresh(),
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
