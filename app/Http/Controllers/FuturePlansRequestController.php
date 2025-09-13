<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FuturePlansRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class FuturePlansRequestController extends Controller
{
    public function index()
    {
        $requests = FuturePlansRequest::all();
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
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:100',

            // Project Details
            'project_type' => 'required|string',
            'queries' => 'nullable|string',

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

        $futurePlansRequest = FuturePlansRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $futurePlansRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Future Plans Request',
            'old_values' => null,
            'new_values' => $futurePlansRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Future Plans Request for project type: " . substr($validatedData['project_type'], 0, 50) . "...",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Future Plans Support Request Submitted - Ref #{$futurePlansRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Future Plans Support Request has been received successfully.\nReference No: {$futurePlansRequest->id}\nProject Type: " . substr($validatedData['project_type'], 0, 100) . "\nWe will review your generous offer and contact you shortly to discuss how we can work together."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Future Plans Support Request has been received.\nReference No: {$futurePlansRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Future plans request created successfully',
            'data' => $futurePlansRequest
        ], 201);
    }

    public function show($id)
    {
        $futurePlansRequest = FuturePlansRequest::find($id);

        if (!$futurePlansRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Future plans request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $futurePlansRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $futurePlansRequest = FuturePlansRequest::find($id);

        if (!$futurePlansRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Future plans request not found'
            ], 404);
        }

        $oldValues = $futurePlansRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',

            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',

            'mobile_number' => 'sometimes|required|string|max:20',
            'whatsapp_number' => 'sometimes|nullable|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',

            'project_type' => 'sometimes|required|string',
            'queries' => 'sometimes|nullable|string',

            'status' => 'sometimes|in:pending,approved,rejected,on_hold',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $futurePlansRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Future Plans Request',
            'description' => "Updated Future Plans request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($futurePlansRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Future plans request updated successfully',
            'data' => $futurePlansRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $futurePlansRequest = FuturePlansRequest::find($id);

        if (!$futurePlansRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Future plans request not found'
            ], 404);
        }

        $oldValues = $futurePlansRequest->getAttributes();

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

        $futurePlansRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($futurePlansRequest->email_address)->send(
                new FormNotificationMail(
                    "Future Plans Support Request Status Updated - Ref #{$futurePlansRequest->id}",
                    "Dear {$futurePlansRequest->first_name},\n\nYour Future Plans Support Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$futurePlansRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $futurePlansRequest->mobile_number,
                "Hello {$futurePlansRequest->first_name}, your Future Plans Support Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$futurePlansRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Future Plans Request',
            'description' => "Updated status for Future Plans request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($futurePlansRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $futurePlansRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $futurePlansRequest = FuturePlansRequest::find($id);

        if (!$futurePlansRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Future plans request not found'
            ], 404);
        }

        $oldValues = $futurePlansRequest->getAttributes();

        $futurePlansRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Future Plans Request',
            'description' => "Deleted Future Plans request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Future plans request deleted successfully'
        ]);
    }
}
