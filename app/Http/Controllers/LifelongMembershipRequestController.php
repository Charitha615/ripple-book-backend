<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LifelongMembershipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class LifelongMembershipRequestController extends Controller
{
    public function index()
    {
        $requests = LifelongMembershipRequest::all();
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

            // Additional Address
            'residential_address' => 'required|string',

            // Additional Information
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

        $lifelongMembershipRequest = LifelongMembershipRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $lifelongMembershipRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Lifelong Membership Request',
            'old_values' => null,
            'new_values' => $lifelongMembershipRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Lifelong Membership Request.",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Lifelong Membership Request Submitted - Ref #{$lifelongMembershipRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Lifelong Membership Request has been received successfully.\nReference No: {$lifelongMembershipRequest->id}\nWe are honored by your interest in becoming a lifelong member of our spiritual community. We will contact you shortly to discuss the membership process and benefits."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Lifelong Membership Request has been received.\nReference No: {$lifelongMembershipRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Lifelong membership request created successfully',
            'data' => $lifelongMembershipRequest
        ], 201);
    }

    public function show($id)
    {
        $lifelongMembershipRequest = LifelongMembershipRequest::find($id);

        if (!$lifelongMembershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Lifelong membership request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lifelongMembershipRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $lifelongMembershipRequest = LifelongMembershipRequest::find($id);

        if (!$lifelongMembershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Lifelong membership request not found'
            ], 404);
        }

        $oldValues = $lifelongMembershipRequest->getAttributes();

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

            'residential_address' => 'sometimes|required|string',

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

        $lifelongMembershipRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Lifelong Membership Request',
            'description' => "Updated Lifelong Membership request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($lifelongMembershipRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lifelong membership request updated successfully',
            'data' => $lifelongMembershipRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $lifelongMembershipRequest = LifelongMembershipRequest::find($id);

        if (!$lifelongMembershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Lifelong membership request not found'
            ], 404);
        }

        $oldValues = $lifelongMembershipRequest->getAttributes();

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

        $lifelongMembershipRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($lifelongMembershipRequest->email_address)->send(
                new FormNotificationMail(
                    "Lifelong Membership Request Status Updated - Ref #{$lifelongMembershipRequest->id}",
                    "Dear {$lifelongMembershipRequest->first_name},\n\nYour Lifelong Membership Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$lifelongMembershipRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $lifelongMembershipRequest->mobile_number,
                "Hello {$lifelongMembershipRequest->first_name}, your Lifelong Membership Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$lifelongMembershipRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Lifelong Membership Request',
            'description' => "Updated status for Lifelong Membership request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($lifelongMembershipRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $lifelongMembershipRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $lifelongMembershipRequest = LifelongMembershipRequest::find($id);

        if (!$lifelongMembershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Lifelong membership request not found'
            ], 404);
        }

        $oldValues = $lifelongMembershipRequest->getAttributes();

        $lifelongMembershipRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Lifelong Membership Request',
            'description' => "Deleted Lifelong Membership request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lifelong membership request deleted successfully'
        ]);
    }
}
