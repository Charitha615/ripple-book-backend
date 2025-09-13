<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class MaintenanceRequestController extends Controller
{
    public function index()
    {
        $requests = MaintenanceRequest::all();
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Personal Information
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',

            // Address Information
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',

            // Contact Information
            'mobile_number' => 'required|string|max:20',
            'phone_with_country_code' => 'required|string|max:20',
            'email_address' => 'required|email|max:100',

            // Maintenance Details
            'maintenance_type' => 'required|string',
            'number_of_volunteers' => 'required|integer|min:1|max:100',
            'preferred_time' => 'required|string',

            // System
            'ip_address' => 'nullable|ip'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['ip_address'] = $request->ip();

        $maintenanceRequest = MaintenanceRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $maintenanceRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Maintenance Request',
            'old_values' => null,
            'new_values' => $maintenanceRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Maintenance Request with " . $validatedData['number_of_volunteers'] . " volunteers for " . substr($validatedData['maintenance_type'], 0, 50) . "...",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Maintenance Support Request Submitted - Ref #{$maintenanceRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Maintenance Support Request has been received successfully.\nReference No: {$maintenanceRequest->id}\nMaintenance Type: " . substr($validatedData['maintenance_type'], 0, 100) . "\nVolunteers: {$validatedData['number_of_volunteers']}\nWe will review your request and contact you shortly to coordinate the maintenance work."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Maintenance Support Request has been received.\nReference No: {$maintenanceRequest->id}\nVolunteers: {$validatedData['number_of_volunteers']}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request created successfully',
            'data' => $maintenanceRequest
        ], 201);
    }

    public function show($id)
    {
        $maintenanceRequest = MaintenanceRequest::find($id);

        if (!$maintenanceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $maintenanceRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::find($id);

        if (!$maintenanceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance request not found'
            ], 404);
        }

        $oldValues = $maintenanceRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',

            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',

            'mobile_number' => 'sometimes|required|string|max:20',
            'phone_with_country_code' => 'sometimes|required|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',

            'maintenance_type' => 'sometimes|required|string',
            'number_of_volunteers' => 'sometimes|required|integer|min:1|max:100',
            'preferred_time' => 'sometimes|required|string',

            'status' => 'sometimes|in:pending,approved,rejected,on_hold',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Maintenance Request',
            'description' => "Updated Maintenance request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($maintenanceRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request updated successfully',
            'data' => $maintenanceRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $maintenanceRequest = MaintenanceRequest::find($id);

        if (!$maintenanceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance request not found'
            ], 404);
        }

        $oldValues = $maintenanceRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected,on_hold',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $maintenanceRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($maintenanceRequest->email_address)->send(
                new FormNotificationMail(
                    "Maintenance Support Request Status Updated - Ref #{$maintenanceRequest->id}",
                    "Dear {$maintenanceRequest->first_name},\n\nYour Maintenance Support Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$maintenanceRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $maintenanceRequest->mobile_number,
                "Hello {$maintenanceRequest['first_name']}, your Maintenance Support Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$maintenanceRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Maintenance Request',
            'description' => "Updated status for Maintenance request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($maintenanceRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $maintenanceRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $maintenanceRequest = MaintenanceRequest::find($id);

        if (!$maintenanceRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Maintenance request not found'
            ], 404);
        }

        $oldValues = $maintenanceRequest->getAttributes();

        $maintenanceRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Maintenance Request',
            'description' => "Deleted Maintenance request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Maintenance request deleted successfully'
        ]);
    }
}
