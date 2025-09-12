<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Models\OrdinationRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class OrdinationRegistrationController extends Controller
{
    public function index()
    {
        $registrations = OrdinationRegistration::all();
        return response()->json([
            'success' => true,
            'data' => $registrations
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
            'marital_status' => 'required|in:single,married',
            'has_permission' => 'required|boolean',

            // Background Check
            'military_service' => 'required|boolean',
            'criminal_record' => 'required|boolean',

            // Ordination Details
            'ordination_type' => 'required|in:short_term,permanent',
            'ordination_time' => 'required|string|max:100',
            'ordination_month' => 'nullable|string|max:50',
            'ordination_year' => 'nullable|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),

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

        $registration = OrdinationRegistration::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $registration->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Ordination Registration',
            'old_values' => null,
            'new_values' => $registration,
            'description' => $validatedData['first_name'] . " " . $validatedData['last_name'] . " submitted an Ordination Registration. Mobile number is " . $validatedData['mobile_number'],
        ]);

        // Send email notification
        try {
            Mail::to($validatedData['email_address'])->send(
                new FormNotificationMail(
                    "Ordination Registration Submitted - Ref #{$registration->id}",
                    "Dear {$validatedData['first_name']},\n\nYour Ordination Registration has been received successfully.\nReference No: {$registration->id}\nWe will review your application and contact you shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email: " . $mailEx->getMessage());
        }

        // Send WhatsApp message
        try {
            WhatsAppService::sendMessage(
                $validatedData['mobile_number'],
                "Hello {$validatedData['first_name']}, your Ordination Registration has been received.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Ordination registration created successfully',
            'data' => $registration
        ], 201);
    }

    public function show($id)
    {
        $registration = OrdinationRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination registration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registration
        ]);
    }

    public function update(Request $request, $id)
    {
        $registration = OrdinationRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

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
            'marital_status' => 'sometimes|required|in:single,married',
            'has_permission' => 'sometimes|required|boolean',

            'military_service' => 'sometimes|required|boolean',
            'criminal_record' => 'sometimes|required|boolean',

            'ordination_type' => 'sometimes|required|in:short_term,permanent',
            'ordination_time' => 'sometimes|required|string|max:100',
            'ordination_month' => 'sometimes|nullable|string|max:50',
            'ordination_year' => 'sometimes|nullable|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),

            'emergency_first_name' => 'sometimes|required|string|max:100',
            'emergency_last_name' => 'sometimes|required|string|max:100',
            'emergency_email' => 'sometimes|required|email|max:100',
            'emergency_relationship' => 'sometimes|required|string|max:100',
            'emergency_mobile_1' => 'sometimes|required|string|max:20',
            'emergency_mobile_2' => 'sometimes|nullable|string|max:20',

            'has_mental_disorder_history' => 'sometimes|required|boolean',
            'has_contagious_disease' => 'sometimes|required|boolean',
            'other_health_complications' => 'sometimes|nullable|string',

            'queries' => 'sometimes|nullable|string',

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
            'entity_area' => 'Ordination Registration',
            'description' => "Updated Ordination registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($registration->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ordination registration updated successfully',
            'data' => $registration->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = OrdinationRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

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

        $registration->update($validator->validated());

        // Send status update notification
        try {
            Mail::to($registration->email_address)->send(
                new FormNotificationMail(
                    "Ordination Registration Status Updated - Ref #{$registration->id}",
                    "Dear {$registration->first_name},\n\nYour Ordination Registration status has been updated to: {$request->status}.\nReference No: {$registration->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update
        try {
            WhatsAppService::sendMessage(
                $registration->mobile_number,
                "Hello {$registration->first_name}, your Ordination Registration status has been updated to: {$request->status}.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Ordination Registration',
            'description' => "Updated status for Ordination registration ID: {$id} to {$request->status}",
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
        $registration = OrdinationRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Ordination registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

        $registration->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Ordination Registration',
            'description' => "Deleted Ordination registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ordination registration deleted successfully'
        ]);
    }
}
