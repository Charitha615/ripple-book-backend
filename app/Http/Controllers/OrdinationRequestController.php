<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\OrdinationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class OrdinationRequestController extends Controller
{
    public function index()
    {
        $requests = OrdinationRequest::all();
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
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:18|max:100',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:100',

            // Ordination Details
            'ordination_type' => 'required|in:short_term,permanent',
            'ordination_month' => 'required|string|max:50',
            'ordination_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),

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

        $ordinationRequest = OrdinationRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $ordinationRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Ordination Request',
            'old_values' => null,
            'new_values' => $ordinationRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted an Ordination Request. Mobile number is " . $validatedData['mobile_number'],
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Ordination Request Submitted - Ref #{$ordinationRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Ordination Request has been received successfully.\nReference No: {$ordinationRequest->id}\nWe will review your request and contact you shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Ordination Request has been received.\nReference No: {$ordinationRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordination request created successfully',
            'data' => $ordinationRequest
        ], 201);
    }

    public function show($id)
    {
        $ordinationRequest = OrdinationRequest::find($id);

        if (!$ordinationRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $ordinationRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $ordinationRequest = OrdinationRequest::find($id);

        if (!$ordinationRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination request not found'
            ], 404);
        }

        $oldValues = $ordinationRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'date_of_birth' => 'sometimes|required|date',
            'age' => 'sometimes|required|integer|min:18|max:100',
            'gender' => 'sometimes|required|in:male,female,other',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'mobile_number' => 'sometimes|required|string|max:20',
            'whatsapp_number' => 'sometimes|nullable|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',

            'ordination_type' => 'sometimes|required|in:short_term,permanent',
            'ordination_month' => 'sometimes|required|string|max:50',
            'ordination_year' => 'sometimes|required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),

            'queries' => 'sometimes|nullable|string',

            'status' => 'sometimes|in:Pending,Approved,Rejected,On hold',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $ordinationRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Ordination Request',
            'description' => "Updated Ordination request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($ordinationRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ordination request updated successfully',
            'data' => $ordinationRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $ordinationRequest = OrdinationRequest::find($id);

        if (!$ordinationRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination request not found'
            ], 404);
        }

        $oldValues = $ordinationRequest->getAttributes();

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

        $ordinationRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($ordinationRequest->email_address)->send(
                new FormNotificationMail(
                    "Ordination Request Status Updated - Ref #{$ordinationRequest->id}",
                    "Dear {$ordinationRequest->first_name},\n\nYour Ordination Request status has been updated to: {$request->status}.\nReference No: {$ordinationRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $ordinationRequest->mobile_number,
                "Hello {$ordinationRequest->first_name}, your Ordination Request status has been updated to: {$request->status}.\nReference No: {$ordinationRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Ordination Request',
            'description' => "Updated status for Ordination request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($ordinationRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $ordinationRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $ordinationRequest = OrdinationRequest::find($id);

        if (!$ordinationRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination request not found'
            ], 404);
        }

        $oldValues = $ordinationRequest->getAttributes();

        $ordinationRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Ordination Request',
            'description' => "Deleted Ordination request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ordination request deleted successfully'
        ]);
    }
}
