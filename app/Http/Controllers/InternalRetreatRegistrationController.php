<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\InternalRetreatRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class InternalRetreatRegistrationController extends Controller
{
    public function index()
    {
        $registrations = InternalRetreatRegistration::all();
        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Basic Information
            'access_code' => 'nullable|string|max:50',
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'age' => 'required|integer|min:18|max:100',
            'religion' => 'required|string|max:100',
            'gender' => 'required|in:male,female,other',
            'address' => 'required|string',
            'street_address' => 'required|string|max:255',
            'street_address_line_2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'mobile_number' => 'required|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'email_address' => 'required|email|max:100',
            'is_experienced_meditator' => 'required|boolean',

            // Emergency Contact
            'emergency_first_name' => 'required|string|max:100',
            'emergency_last_name' => 'required|string|max:100',
            'emergency_email' => 'required|email|max:100',
            'emergency_relationship' => 'required|string|max:100',
            'emergency_mobile_1' => 'required|string|max:20',
            'emergency_mobile_2' => 'nullable|string|max:20',

            // Medical History
            'has_mental_disorder_history' => 'required|boolean',
            'has_contagious_disease' => 'required|boolean',
            'other_health_complications' => 'nullable|string',

            // Retreat Attendance
            'retreat_no' => 'required|string|max:50',
            'attend_full_retreat' => 'required|boolean',
            'preferred_roommate_name' => 'nullable|string|max:100',
            'number_of_days' => 'required|integer|min:1',
            'arrival_date' => 'required|date',
            'departure_date' => 'required|date|after_or_equal:arrival_date',
            'monastic_status' => 'required|in:none,bhikkhu,nun,samanera,anagarika',

            // PDF
            'pdf_base64' => 'nullable|string',
            'pdf_filename' => 'nullable|string|max:255',

            // Declaration
            'declaration_full_name' => 'required|string|max:100',
            'declaration_date' => 'required|date',

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

        $registration = InternalRetreatRegistration::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $registration->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Internal Retreat Registration',
            'old_values' => null,
            'new_values' => $registration,
            'description' => $validatedData['first_name'] . " submitted an Internal Retreat Registration. Mobile number is " . $validatedData['mobile_number'],
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Internal Retreat Registration Submitted - Ref #{$registration->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Internal Retreat Registration has been received successfully.\nReference No: {$registration->id}\nWe will review it shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Internal Retreat Registration has been received.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat registration created successfully',
            'data' => $registration
        ], 201);
    }

    public function show($id)
    {
        $registration = InternalRetreatRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat registration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registration
        ]);
    }

    public function update(Request $request, $id)
    {
        $registration = InternalRetreatRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'access_code' => 'sometimes|nullable|string|max:50',
            'first_name' => 'sometimes|required|string|max:100',
            'last_name' => 'sometimes|required|string|max:100',
            'age' => 'sometimes|required|integer|min:18|max:100',
            'religion' => 'sometimes|required|string|max:100',
            'gender' => 'sometimes|required|in:male,female,other',
            'address' => 'sometimes|required|string',
            'street_address' => 'sometimes|required|string|max:255',
            'street_address_line_2' => 'sometimes|nullable|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'mobile_number' => 'sometimes|required|string|max:20',
            'whatsapp_number' => 'sometimes|nullable|string|max:20',
            'email_address' => 'sometimes|required|email|max:100',
            'is_experienced_meditator' => 'sometimes|required|boolean',

            'emergency_first_name' => 'sometimes|required|string|max:100',
            'emergency_last_name' => 'sometimes|required|string|max:100',
            'emergency_email' => 'sometimes|required|email|max:100',
            'emergency_relationship' => 'sometimes|required|string|max:100',
            'emergency_mobile_1' => 'sometimes|required|string|max:20',
            'emergency_mobile_2' => 'sometimes|nullable|string|max:20',

            'has_mental_disorder_history' => 'sometimes|required|boolean',
            'has_contagious_disease' => 'sometimes|required|boolean',
            'other_health_complications' => 'sometimes|nullable|string',

            'retreat_no' => 'sometimes|required|string|max:50',
            'attend_full_retreat' => 'sometimes|required|boolean',
            'preferred_roommate_name' => 'sometimes|nullable|string|max:100',
            'number_of_days' => 'sometimes|required|integer|min:1',
            'arrival_date' => 'sometimes|required|date',
            'departure_date' => 'sometimes|required|date|after_or_equal:arrival_date',
            'monastic_status' => 'sometimes|required|in:none,bhikkhu,nun,samanera,anagarika',

            'pdf_base64' => 'sometimes|nullable|string',
            'pdf_filename' => 'sometimes|nullable|string|max:255',

            'declaration_full_name' => 'sometimes|required|string|max:100',
            'declaration_date' => 'sometimes|required|date',

            'status' => 'sometimes|in:pending,approved,rejected,processing',
            'status_reason' => 'sometimes|nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $registration->update($validator->validated());

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Update',
            'entity_area' => 'Internal Retreat Registration',
            'description' => "Updated Internal Retreat registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($registration->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat registration updated successfully',
            'data' => $registration->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = InternalRetreatRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

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

        $registration->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($registration->email_address)->send(
                new FormNotificationMail(
                    "Internal Retreat Registration Status Updated - Ref #{$registration->id}",
                    "Dear {$registration->first_name},\n\nYour Internal Retreat Registration status has been updated to: {$request->status}.\nReference No: {$registration->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $registration->mobile_number,
                "Hello {$registration->first_name}, your Internal Retreat Registration status has been updated to: {$request->status}.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Internal Retreat Registration',
            'description' => "Updated status for Internal Retreat registration ID: {$id} to {$request->status}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($registration->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Status updated successfully',
            'data' => $registration->fresh()
        ]);
    }

    public function destroy($id)
    {
        $registration = InternalRetreatRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Internal retreat registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

        $registration->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Internal Retreat Registration',
            'description' => "Deleted Internal Retreat registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Internal retreat registration deleted successfully'
        ]);
    }
}
