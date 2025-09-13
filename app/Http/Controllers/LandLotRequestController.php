<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\LandLotRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class LandLotRequestController extends Controller
{
    public function index()
    {
        $requests = LandLotRequest::all();
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

            // Land Lot Details
            'land_lot_numbers' => 'required|array',
            'land_lot_numbers.*' => 'string|max:50',

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

        $landLotRequest = LandLotRequest::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $landLotRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Land Lot Request',
            'old_values' => null,
            'new_values' => $landLotRequest,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted a Land Lot Request for lots: " . implode(', ', $validatedData['land_lot_numbers']),
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Land Lot Donation Request Submitted - Ref #{$landLotRequest->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Land Lot Donation Request has been received successfully.\nReference No: {$landLotRequest->id}\nRequested Lots: " . implode(', ', $validatedData['land_lot_numbers']) . "\nWe will review your request and contact you shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Land Lot Donation Request has been received.\nReference No: {$landLotRequest->id}\nLots: " . implode(', ', $validatedData['land_lot_numbers'])
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Land lot request created successfully',
            'data' => $landLotRequest
        ], 201);
    }

    public function show($id)
    {
        $landLotRequest = LandLotRequest::find($id);

        if (!$landLotRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Land lot request not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $landLotRequest
        ]);
    }

    public function update(Request $request, $id)
    {
        $landLotRequest = LandLotRequest::find($id);

        if (!$landLotRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Land lot request not found'
            ], 404);
        }

        $oldValues = $landLotRequest->getAttributes();

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

            'land_lot_numbers' => 'sometimes|required|array',
            'land_lot_numbers.*' => 'string|max:50',

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

        $landLotRequest->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Land Lot Request',
            'description' => "Updated Land Lot request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($landLotRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Land lot request updated successfully',
            'data' => $landLotRequest->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $landLotRequest = LandLotRequest::find($id);

        if (!$landLotRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Land lot request not found'
            ], 404);
        }

        $oldValues = $landLotRequest->getAttributes();

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

        $landLotRequest->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($landLotRequest->email_address)->send(
                new FormNotificationMail(
                    "Land Lot Donation Request Status Updated - Ref #{$landLotRequest->id}",
                    "Dear {$landLotRequest->first_name},\n\nYour Land Lot Donation Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$landLotRequest->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $landLotRequest->mobile_number,
                "Hello {$landLotRequest->first_name}, your Land Lot Donation Request status has been updated to: " . ucfirst(str_replace('_', ' ', $request->status)) . ".\nReference No: {$landLotRequest->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Land Lot Request',
            'description' => "Updated status for Land Lot request ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($landLotRequest->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $landLotRequest->fresh()
        ]);
    }

    public function destroy($id)
    {
        $landLotRequest = LandLotRequest::find($id);

        if (!$landLotRequest) {
            return response()->json([
                'success' => false,
                'message' => 'Land lot request not found'
            ], 404);
        }

        $oldValues = $landLotRequest->getAttributes();

        $landLotRequest->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Land Lot Request',
            'description' => "Deleted Land Lot request ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Land lot request deleted successfully'
        ]);
    }
}
