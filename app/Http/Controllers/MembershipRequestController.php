<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\MembershipRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class MembershipRequestController extends Controller
{
    public function index()
    {
        $requests = MembershipRequest::all();
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

            // Donation Details
            'donation_purpose' => 'required|in:birthday,wedding_anniversary,punyanumoda,other',
            'other_purpose' => 'nullable|string|max:200|required_if:donation_purpose,other',
            'donation_type' => 'required|in:individual,group',
            'payment_method' => 'required|in:online_payment,monthly_direct_debit,cash_deposit',

            // Signature and Date
            'signature' => 'required|string|max:255',
            'application_date' => 'required|date',

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

        $membershipRequest = MembershipRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $membershipRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Membership Request',
            'old_values' => null,
            'new_values' => $membershipRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Membership Request for 5 Year Arama Pooja Project. Purpose: " . $validatedData['donation_purpose'],
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "5 Year Arama Pooja Project Membership Request Submitted - Ref #{$membershipRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Membership Request for the 5 Year Arama Pooja Project has been received successfully.\nReference No: {$membershipRequest->id}\nPurpose: " . ucfirst(str_replace('_', ' ', $validatedData['donation_purpose'])) . "\nWe will review your request and contact you shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your 5 Year Arama Pooja Project Membership Request has been received.\nReference No: {$membershipRequest->id}\nPurpose: " . ucfirst(str_replace('_', ' ', $validatedData['donation_purpose']))
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Membership request created successfully',
            'data' => $membershipRequest
        ], 201);
    }

    public function show($id)
    {
        $membershipRequest = MembershipRequest::find($id);

        if (!$membershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Membership request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $membershipRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $membershipRequest = MembershipRequest::find($id);

        if (!$membershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Membership request not found'
            ], 404);
        }

        $oldValues = $membershipRequest->getAttributes();

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

            'donation_purpose' => 'sometimes|required|in:birthday,wedding_anniversary,punyanumoda,other',
            'other_purpose' => 'sometimes|nullable|string|max:200|required_if:donation_purpose,other',
            'donation_type' => 'sometimes|required|in:individual,group',
            'payment_method' => 'sometimes|required|in:online_payment,monthly_direct_debit,cash_deposit',

            'signature' => 'sometimes|required|string|max:255',
            'application_date' => 'sometimes|required|date',

            'status' => 'sometimes|in:pending,approved,rejected,on_hold',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $membershipRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Membership Request',
            'description' => "Updated Membership request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($membershipRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Membership request updated successfully',
            'data' => $membershipRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $membershipRequest = MembershipRequest::find($id);

        if (!$membershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Membership request not found'
            ], 404);
        }

        $oldValues = $membershipRequest->getAttributes();

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

        $membershipRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($membershipRequest->email_address)->send(
                new FormNotificationMail(
                    "5 Year Arama Pooja Project Membership Status Updated - Ref #{$membershipRequest->id}",
                    "Dear {$membershipRequest->first_name},\n\nYour Membership Request status for the 5 Year Arama Pooja Project has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$membershipRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $membershipRequest->mobile_number,
                "Hello {$membershipRequest->first_name}, your 5 Year Arama Pooja Project Membership status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$membershipRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Membership Request',
            'description' => "Updated status for Membership request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($membershipRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $membershipRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $membershipRequest = MembershipRequest::find($id);

        if (!$membershipRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Membership request not found'
            ], 404);
        }

        $oldValues = $membershipRequest->getAttributes();

        $membershipRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Membership Request',
            'description' => "Deleted Membership request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Membership request deleted successfully'
        ]);
    }
}
