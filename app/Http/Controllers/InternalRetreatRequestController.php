<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\InternalRetreatRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class InternalRetreatRequestController extends Controller
{
    public function index()
    {
        $requests = InternalRetreatRequest::with('internalRetreat')->get();
        return response()->json([
            'success' => true,
            'data' => $requests
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'internal_retreat_id' => 'nullable|exists:internal_retreats,id',
            'retreat_no' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:100',
            'gender' => 'required|in:male,female,other',
            'interested_retreat_number' => 'required|string|max:100',
            'preferred_dates' => 'required|array',
            'preferred_dates.*' => 'date',
            'queries' => 'nullable|string',
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

        $internalRetreatRequest = InternalRetreatRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $internalRetreatRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Internal Retreat Request',
            'old_values' => null,
            'new_values' => $internalRetreatRequest,
            'description' => $validatedData['first_name'] . " submitted an Internal Retreat Request. Mobile number is " . $validatedData['mobile_number'],
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Internal Retreat Request Submitted - Ref #{$internalRetreatRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Internal Retreat Request has been received successfully.\nReference No: {$internalRetreatRequest->id}\nWe will review it shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Internal Retreat Request has been received.\nReference No: {$internalRetreatRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat request created successfully',
            'data' => $internalRetreatRequest->load('internalRetreat')
        ], 201);
    }

    public function show($id)
    {
        $internalRetreatRequest = InternalRetreatRequest::with('internalRetreat')->find($id);

        if (!$internalRetreatRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $internalRetreatRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $internalRetreatRequest = InternalRetreatRequest::find($id);

        if (!$internalRetreatRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat request not found'
            ], 404);
        }

        $oldValues = $internalRetreatRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            'internal_retreat_id' => 'nullable|exists:internal_retreats,id',
            'retreat_no' => 'nullable|string|max:50',
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'mobile_number' => 'sometimes|required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',
            'gender' => 'sometimes|required|in:male,female,other',
            'interested_retreat_number' => 'sometimes|required|string|max:100',
            'preferred_dates' => 'sometimes|required|array',
            'preferred_dates.*' => 'date',
            'queries' => 'nullable|string',
            'status' => 'sometimes|in:pending,approved,rejected,processing',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $internalRetreatRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Internal Retreat Request',
            'description' => "Updated Internal Retreat request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($internalRetreatRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat request updated successfully',
            'data' => $internalRetreatRequest->fresh()->load('internalRetreat')
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $internalRetreatRequest = InternalRetreatRequest::find($id);

        if (!$internalRetreatRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat request not found'
            ], 404);
        }

        $oldValues = $internalRetreatRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:pending,approved,rejected,processing',
            'status_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $internalRetreatRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($internalRetreatRequest->email_address)->send(
                new FormNotificationMail(
                    "Internal Retreat Request Status Updated - Ref #{$internalRetreatRequest->id}",
                    "Dear {$internalRetreatRequest->first_name},\n\nYour Internal Retreat Request status has been updated to: {$request->status}.\nReference No: {$internalRetreatRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $internalRetreatRequest->mobile_number,
                "Hello {$internalRetreatRequest->first_name}, your Internal Retreat Request status has been updated to: {$request->status}.\nReference No: {$internalRetreatRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Internal Retreat Request',
            'description' => "Updated status for Internal Retreat request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($internalRetreatRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $internalRetreatRequest->fresh()->load('internalRetreat')
        ]);
    }

    public function destroy($id)
    {
        $internalRetreatRequest = InternalRetreatRequest::find($id);

        if (!$internalRetreatRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat request not found'
            ], 404);
        }

        $oldValues = $internalRetreatRequest->getAttributes();

        $internalRetreatRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Internal Retreat Request',
            'description' => "Deleted Internal Retreat request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat request deleted successfully'
        ]);
    }
}
