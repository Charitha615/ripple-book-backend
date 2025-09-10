<?php
// app/Http/Controllers/PirikaraRequestController.php

namespace App\Http\Controllers;

use App\Models\PirikaraRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PirikaraRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $requests = PirikaraRequest::all();
        return response()->json($requests);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'required|string|max:20',
            'email_address' => 'required|email',
            'pirikara_type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pirikaraRequest = PirikaraRequest::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'mobile_number' => $request->mobile_number,
            'whatsapp_number' => $request->whatsapp_number,
            'email_address' => $request->email_address,
            'pirikara_type' => $request->pirikara_type,
            'ip_address' => $request->ip(),
            'status' => 'Pending'
        ]);

        // Create user log
        if (class_exists('App\Http\Controllers\UserLogController')) {
            UserLogController::createLog([
                'user_id' => null,
                'form_id' => $pirikaraRequest->id,
                'action_type' => 'form_submission',
                'entity_area' => 'Pirikara Request',
                'old_values' => json_encode([]),
                'new_values' => json_encode($pirikaraRequest->getAttributes()),
                'description' => 'New Pirikara request submitted',
            ]);
        }

        return response()->json([
            'message' => 'Pirikara request submitted successfully',
            'data' => $pirikaraRequest
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $pirikaraRequest = PirikaraRequest::findOrFail($id);
        return response()->json($pirikaraRequest);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pirikaraRequest = PirikaraRequest::findOrFail($id);
        $oldValues = $pirikaraRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
            'mobile_number' => 'sometimes|required|string|max:20',
            'whatsapp_number' => 'sometimes|required|string|max:20',
            'email_address' => 'sometimes|required|email',
            'pirikara_type' => 'sometimes|required',
            'status' => 'sometimes|required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pirikaraRequest->update($request->all());

        // Create admin audit log
        if (class_exists('App\Http\Controllers\AuditAdminLogController') && Auth::check()) {
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Pirikara Request',
                'description' => "Updated Pirikara request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($pirikaraRequest->fresh()->getAttributes()),
            ]);
        }

        return response()->json([
            'message' => 'Pirikara request updated successfully',
            'data' => $pirikaraRequest
        ]);
    }

    /**
     * Update the status of the specified resource.
     */
    public function updateStatus(Request $request, string $id)
    {
        $pirikaraRequest = PirikaraRequest::findOrFail($id);
        $oldValues = $pirikaraRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $pirikaraRequest->update([
            'status' => $request->status,
            'status_reason' => $request->status_reason
        ]);

        // Create admin audit log
        if (class_exists('App\Http\Controllers\AuditAdminLogController') && Auth::check()) {
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Status Update',
                'entity_area' => 'Pirikara Request',
                'description' => "Updated status of Pirikara request ID: {$id} to {$request->status}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($pirikaraRequest->fresh()->getAttributes()),
            ]);
        }

        return response()->json([
            'message' => 'Pirikara request status updated successfully',
            'data' => $pirikaraRequest
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pirikaraRequest = PirikaraRequest::findOrFail($id);
        $oldValues = $pirikaraRequest->getAttributes();

        $pirikaraRequest->delete();

        // Create admin audit log
        if (class_exists('App\Http\Controllers\AuditAdminLogController') && Auth::check()) {
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Pirikara Request',
                'description' => "Deleted Pirikara request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode([]),
            ]);
        }

        return response()->json(['message' => 'Pirikara request deleted successfully']);
    }
}
