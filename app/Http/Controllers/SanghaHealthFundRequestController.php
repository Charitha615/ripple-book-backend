<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SanghaHealthFundRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class SanghaHealthFundRequestController extends Controller
{
    public function index()
    {
        $requests = SanghaHealthFundRequest::all();
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

        $sanghaHealthFundRequest = SanghaHealthFundRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $sanghaHealthFundRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Sangha Health Fund Request',
            'old_values' => null,
            'new_values' => $sanghaHealthFundRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Sangha Health Fund Request.",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Sangha Health Fund Donation Request Submitted - Ref #{$sanghaHealthFundRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Sangha Health Fund Donation Request has been received successfully.\nReference No: {$sanghaHealthFundRequest->id}\nWe deeply appreciate your generosity in supporting the health and wellbeing of our Sangha community. We will contact you shortly to discuss the donation process."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Sangha Health Fund Donation Request has been received.\nReference No: {$sanghaHealthFundRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Sangha health fund request created successfully',
            'data' => $sanghaHealthFundRequest
        ], 201);
    }

    public function show($id)
    {
        $sanghaHealthFundRequest = SanghaHealthFundRequest::find($id);

        if (!$sanghaHealthFundRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Sangha health fund request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sanghaHealthFundRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $sanghaHealthFundRequest = SanghaHealthFundRequest::find($id);

        if (!$sanghaHealthFundRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Sangha health fund request not found'
            ], 404);
        }

        $oldValues = $sanghaHealthFundRequest->getAttributes();

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

        $sanghaHealthFundRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Sangha Health Fund Request',
            'description' => "Updated Sangha Health Fund request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($sanghaHealthFundRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sangha health fund request updated successfully',
            'data' => $sanghaHealthFundRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $sanghaHealthFundRequest = SanghaHealthFundRequest::find($id);

        if (!$sanghaHealthFundRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Sangha health fund request not found'
            ], 404);
        }

        $oldValues = $sanghaHealthFundRequest->getAttributes();

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

        $sanghaHealthFundRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($sanghaHealthFundRequest->email_address)->send(
                new FormNotificationMail(
                    "Sangha Health Fund Donation Request Status Updated - Ref #{$sanghaHealthFundRequest->id}",
                    "Dear {$sanghaHealthFundRequest->first_name},\n\nYour Sangha Health Fund Donation Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$sanghaHealthFundRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $sanghaHealthFundRequest->mobile_number,
                "Hello {$sanghaHealthFundRequest->first_name}, your Sangha Health Fund Donation Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$sanghaHealthFundRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Sangha Health Fund Request',
            'description' => "Updated status for Sangha Health Fund request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($sanghaHealthFundRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $sanghaHealthFundRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $sanghaHealthFundRequest = SanghaHealthFundRequest::find($id);

        if (!$sanghaHealthFundRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Sangha health fund request not found'
            ], 404);
        }

        $oldValues = $sanghaHealthFundRequest->getAttributes();

        $sanghaHealthFundRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Sangha Health Fund Request',
            'description' => "Deleted Sangha Health Fund request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sangha health fund request deleted successfully'
        ]);
    }
}
