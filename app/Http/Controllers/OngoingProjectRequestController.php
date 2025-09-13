<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\OngoingProjectRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class OngoingProjectRequestController extends Controller
{
    public function index()
    {
        $requests = OngoingProjectRequest::all();
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

            // Project Details
            'work_types' => 'required|string',
            'number_of_volunteers' => 'required|integer|min:1|max:100',
            'preferred_time' => 'required|string',
            'requires_accommodation' => 'required|boolean',

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

        $projectRequest = OngoingProjectRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $projectRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Ongoing Project Request',
            'old_values' => null,
            'new_values' => $projectRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted an Ongoing Project Request with " . $validatedData['number_of_volunteers'] . " volunteers.",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Ongoing Project Volunteer Request Submitted - Ref #{$projectRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Ongoing Project Volunteer Request has been received successfully.\nReference No: {$projectRequest->id}\nNumber of Volunteers: {$validatedData['number_of_volunteers']}\nWe will review your request and contact you shortly to coordinate the volunteer work."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Ongoing Project Volunteer Request has been received.\nReference No: {$projectRequest->id}\nVolunteers: {$validatedData['number_of_volunteers']}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Ongoing project request created successfully',
            'data' => $projectRequest
        ], 201);
    }

    public function show($id)
    {
        $projectRequest = OngoingProjectRequest::find($id);

        if (!$projectRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ongoing project request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $projectRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $projectRequest = OngoingProjectRequest::find($id);

        if (!$projectRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ongoing project request not found'
            ], 404);
        }

        $oldValues = $projectRequest->getAttributes();

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

            'work_types' => 'sometimes|required|string',
            'number_of_volunteers' => 'sometimes|required|integer|min:1|max:100',
            'preferred_time' => 'sometimes|required|string',
            'requires_accommodation' => 'sometimes|required|boolean',

            'status' => 'sometimes|in:pending,approved,rejected,on_hold',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $projectRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Ongoing Project Request',
            'description' => "Updated Ongoing Project request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($projectRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ongoing project request updated successfully',
            'data' => $projectRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $projectRequest = OngoingProjectRequest::find($id);

        if (!$projectRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ongoing project request not found'
            ], 404);
        }

        $oldValues = $projectRequest->getAttributes();

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

        $projectRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($projectRequest->email_address)->send(
                new FormNotificationMail(
                    "Ongoing Project Volunteer Request Status Updated - Ref #{$projectRequest->id}",
                    "Dear {$projectRequest->first_name},\n\nYour Ongoing Project Volunteer Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$projectRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $projectRequest->mobile_number,
                "Hello {$projectRequest->first_name}, your Ongoing Project Volunteer Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$projectRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Ongoing Project Request',
            'description' => "Updated status for Ongoing Project request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($projectRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $projectRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $projectRequest = OngoingProjectRequest::find($id);

        if (!$projectRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ongoing project request not found'
            ], 404);
        }

        $oldValues = $projectRequest->getAttributes();

        $projectRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Ongoing Project Request',
            'description' => "Deleted Ongoing Project request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ongoing project request deleted successfully'
        ]);
    }
}
