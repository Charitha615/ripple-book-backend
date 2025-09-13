<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\IndividualPoojaRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class IndividualPoojaRequestController extends Controller
{
    public function index()
    {
        $requests = IndividualPoojaRequest::all();
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
            'gender' => 'required|in:male,female,other',

            // Address Information
            'address' => 'required|string',
            'street_address' => 'required|string|max:255',
            'street_address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',

            // Contact Information
            'whatsapp_number' => 'nullable|string|max:20',
            'mobile_number' => 'required|string|max:20',
            'email_address' => 'required|email|max:100',

            // Pooja Purpose
            'for_birthday' => 'sometimes|boolean',
            'for_wedding_anniversary' => 'sometimes|boolean',
            'for_punyanumoda' => 'sometimes|boolean',
            'for_other' => 'sometimes|boolean',
            'other_purpose' => 'nullable|string|max:200|required_if:for_other,true',

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

        // Ensure at least one purpose is selected
        $purposes = [
            $request->for_birthday,
            $request->for_wedding_anniversary,
            $request->for_punyanumoda,
            $request->for_other
        ];

        if (!in_array(true, $purposes, true)) {
            return response()->json([
                'success' => false,
                'errors' => ['purpose' => ['At least one purpose must be selected']]
            ], 422);
        }

        $validatedData = $validator->validated();
        $validatedData['ip_address'] = $request->ip();

        // Set default false for purposes if not provided
        $validatedData['for_birthday'] = $request->for_birthday ?? false;
        $validatedData['for_wedding_anniversary'] = $request->for_wedding_anniversary ?? false;
        $validatedData['for_punyanumoda'] = $request->for_punyanumoda ?? false;
        $validatedData['for_other'] = $request->for_other ?? false;

        $poojaRequest = IndividualPoojaRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $poojaRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Individual Pooja Request',
            'old_values' => null,
            'new_values' => $poojaRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted an Individual Pooja Request.",
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Individual Pooja Request Submitted - Ref #{$poojaRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Individual Pooja Request has been received successfully.\nReference No: {$poojaRequest->id}\nWe will review your request and contact you shortly to discuss the pooja details."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Individual Pooja Request has been received.\nReference No: {$poojaRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Individual pooja request created successfully',
            'data' => $poojaRequest
        ], 201);
    }

    public function show($id)
    {
        $poojaRequest = IndividualPoojaRequest::find($id);

        if (!$poojaRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Individual pooja request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $poojaRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $poojaRequest = IndividualPoojaRequest::find($id);

        if (!$poojaRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Individual pooja request not found'
            ], 404);
        }

        $oldValues = $poojaRequest->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'date_of_birth' => 'sometimes|required|date',
            'gender' => 'sometimes|required|in:male,female,other',

            'address' => 'sometimes|required|string',
            'street_address' => 'sometimes|required|string|max:255',
            'street_address_line_2' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:100',

            'whatsapp_number' => 'sometimes|nullable|string|max:20',
            'mobile_number' => 'sometimes|required|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',

            'for_birthday' => 'sometimes|boolean',
            'for_wedding_anniversary' => 'sometimes|boolean',
            'for_punyanumoda' => 'sometimes|boolean',
            'for_other' => 'sometimes|boolean',
            'other_purpose' => 'sometimes|nullable|string|max:200|required_if:for_other,true',

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

        $poojaRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Individual Pooja Request',
            'description' => "Updated Individual Pooja request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($poojaRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Individual pooja request updated successfully',
            'data' => $poojaRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $poojaRequest = IndividualPoojaRequest::find($id);

        if (!$poojaRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Individual pooja request not found'
            ], 404);
        }

        $oldValues = $poojaRequest->getAttributes();

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

        $poojaRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($poojaRequest->email_address)->send(
                new FormNotificationMail(
                    "Individual Pooja Request Status Updated - Ref #{$poojaRequest->id}",
                    "Dear {$poojaRequest->first_name},\n\nYour Individual Pooja Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$poojaRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $poojaRequest->mobile_number,
                "Hello {$poojaRequest->first_name}, your Individual Pooja Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$poojaRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Individual Pooja Request',
            'description' => "Updated status for Individual Pooja request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($poojaRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $poojaRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $poojaRequest = IndividualPoojaRequest::find($id);

        if (!$poojaRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Individual pooja request not found'
            ], 404);
        }

        $oldValues = $poojaRequest->getAttributes();

        $poojaRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Individual Pooja Request',
            'description' => "Deleted Individual Pooja request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Individual pooja request deleted successfully'
        ]);
    }
}
