<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AuditAdminLogController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\UserLogController;
use App\Models\GuestSpeakerRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\FormNotificationMail;
use App\Services\WhatsAppService;

class GuestSpeakerRegistrationController extends Controller
{
    public function index()
    {
        $registrations = GuestSpeakerRegistration::all();
        return response()->json([
            'success' => true,
            'data' => $registrations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Organiser Details
            'organiser_full_name' => 'required|string|max:100',
            'organiser_dob' => 'required|date',
            'organiser_age' => 'required|integer|min:18|max:100',
            'organiser_mobile_number' => 'required|string|max:20',
            'organiser_whatsapp_number' => 'nullable|string|max:20',
            'organiser_email' => 'required|email|max:100',

            // Guest Speaker Details
            'speaker_first_name' => 'required|string|max:100',
            'speaker_last_name' => 'required|string|max:100',
            'speaker_dob' => 'required|date',
            'speaker_age' => 'required|integer|min:18|max:100',
            'speaker_gender' => 'required|in:male,female,other',
            'speaker_type' => 'required|in:upasampada_monk,samanera_monk,nun,layman',
            'vassa_years' => 'nullable|integer|min:0|required_if:speaker_type,upasampada_monk',
            'samanera_years' => 'nullable|integer|min:0|required_if:speaker_type,samanera_monk',
            'nun_years' => 'nullable|integer|min:0|required_if:speaker_type,nun',

            // Residence Details
            'monastery_name' => 'required|string|max:200',
            'country_of_residence' => 'required|string|max:100',
            'address' => 'required|string',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'speaker_mobile_number' => 'required|string|max:20',
            'speaker_phone_with_country_code' => 'required|string|max:20',
            'speaker_email' => 'required|email|max:100',

            // Experience Details
            'experience_level' => 'required|in:beginner,experienced_teacher',
            'retreat_experience_value' => 'required|integer|min:0',
            'retreat_experience_unit' => 'required|in:months,years',

            // Retreat Program Details
            'retreat_duration' => 'required|in:1_day,4_days,7_days,10_days',
            'preferred_days' => 'required|string|max:50',
            'preferred_month' => 'required|string|max:50',
            'preferred_year' => 'required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),
            'expected_participants' => 'required|integer|min:1|max:1000',

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

        $registration = GuestSpeakerRegistration::create($validatedData);

        // Create user log
        UserLogController::createLog([
            'user_id' => null,
            'form_id' => $registration->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Guest Speaker Registration',
            'old_values' => null,
            'new_values' => $registration,
            'description' => $validatedData['organiser_full_name'] . " submitted a Guest Speaker Registration for " . $validatedData['speaker_first_name'] . " " . $validatedData['speaker_last_name'],
        ]);

        // Send email notification to organiser
        try {
            Mail::to($validatedData['organiser_email'])->send(
                new FormNotificationMail(
                    "Guest Speaker Registration Submitted - Ref #{$registration->id}",
                    "Dear {$validatedData['organiser_full_name']},\n\nYour Guest Speaker Registration for {$validatedData['speaker_first_name']} {$validatedData['speaker_last_name']} has been received successfully.\nReference No: {$registration->id}\nWe will review it shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email to organiser: " . $mailEx->getMessage());
        }

        // Send email notification to speaker
        try {
            Mail::to($validatedData['speaker_email'])->send(
                new FormNotificationMail(
                    "Guest Speaker Registration Submitted - Ref #{$registration->id}",
                    "Dear {$validatedData['speaker_first_name']},\n\nA Guest Speaker Registration has been submitted on your behalf by {$validatedData['organiser_full_name']}.\nReference No: {$registration->id}\nWe will review it shortly."
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send email to speaker: " . $mailEx->getMessage());
        }

        // Send WhatsApp message to organiser
        try {
            WhatsAppService::sendMessage(
                $validatedData['organiser_mobile_number'],
                "Hello {$validatedData['organiser_full_name']}, your Guest Speaker Registration for {$validatedData['speaker_first_name']} has been received.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Guest speaker registration created successfully',
            'data' => $registration
        ], 201);
    }

    public function show($id)
    {
        $registration = GuestSpeakerRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Guest speaker registration not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $registration
        ]);
    }

    public function update(Request $request, $id)
    {
        $registration = GuestSpeakerRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Guest speaker registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

        $validator = Validator::make($request->all(), [
            // Update validation rules (same as store but with 'sometimes')
            'organiser_full_name' => 'sometimes|required|string|max:100',
            'organiser_dob' => 'sometimes|required|date',
            'organiser_age' => 'sometimes|required|integer|min:18|max:100',
            'organiser_mobile_number' => 'sometimes|required|string|max:20',
            'organiser_whatsapp_number' => 'sometimes|nullable|string|max:20',
            'organiser_email' => 'sometimes|required|email|max:100',

            'speaker_first_name' => 'sometimes|required|string|max:100',
            'speaker_last_name' => 'sometimes|required|string|max:100',
            'speaker_dob' => 'sometimes|required|date',
            'speaker_age' => 'sometimes|required|integer|min:18|max:100',
            'speaker_gender' => 'sometimes|required|in:male,female,other',
            'speaker_type' => 'sometimes|required|in:upasampada_monk,samanera_monk,nun,layman',
            'vassa_years' => 'sometimes|nullable|integer|min:0|required_if:speaker_type,upasampada_monk',
            'samanera_years' => 'sometimes|nullable|integer|min:0|required_if:speaker_type,samanera_monk',
            'nun_years' => 'sometimes|nullable|integer|min:0|required_if:speaker_type,nun',

            'monastery_name' => 'sometimes|required|string|max:200',
            'country_of_residence' => 'sometimes|required|string|max:100',
            'address' => 'sometimes|required|string',
            'city' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:100',
            'speaker_mobile_number' => 'sometimes|required|string|max:20',
            'speaker_phone_with_country_code' => 'sometimes|required|string|max:20',
            'speaker_email' => 'sometimes|required|email|max:100',

            'experience_level' => 'sometimes|required|in:beginner,experienced_teacher',
            'retreat_experience_value' => 'sometimes|required|integer|min:0',
            'retreat_experience_unit' => 'sometimes|required|in:months,years',

            'retreat_duration' => 'sometimes|required|in:1_day,4_days,7_days,10_days',
            'preferred_days' => 'sometimes|required|string|max:50',
            'preferred_month' => 'sometimes|required|string|max:50',
            'preferred_year' => 'sometimes|required|integer|min:' . date('Y') . '|max:' . (date('Y') + 5),
            'expected_participants' => 'sometimes|required|integer|min:1|max:1000',

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
            'entity_area' => 'Guest Speaker Registration',
            'description' => "Updated Guest Speaker registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => json_encode($registration->fresh()->getAttributes()),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest speaker registration updated successfully',
            'data' => $registration->fresh()
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $registration = GuestSpeakerRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Guest speaker registration not found'
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

        // Send status update notification to organiser
        try {
            Mail::to($registration->organiser_email)->send(
                new FormNotificationMail(
                    "Guest Speaker Registration Status Updated - Ref #{$registration->id}",
                    "Dear {$registration->organiser_full_name},\n\nYour Guest Speaker Registration status for {$registration->speaker_first_name} {$registration->speaker_last_name} has been updated to: {$request->status}.\nReference No: {$registration->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email to organiser: " . $mailEx->getMessage());
        }

        // Send status update notification to speaker
        try {
            Mail::to($registration->speaker_email)->send(
                new FormNotificationMail(
                    "Guest Speaker Registration Status Updated - Ref #{$registration->id}",
                    "Dear {$registration->speaker_first_name},\n\nYour Guest Speaker Registration status has been updated to: {$request->status}.\nReference No: {$registration->id}\nReason: {$request->status_reason}"
                )
            );
        } catch (\Exception $mailEx) {
            Log::error("Failed to send status update email to speaker: " . $mailEx->getMessage());
        }

        // Send WhatsApp status update to organiser
        try {
            WhatsAppService::sendMessage(
                $registration->organiser_mobile_number,
                "Hello {$registration->organiser_full_name}, Guest Speaker Registration status for {$registration->speaker_first_name} has been updated to: {$request->status}.\nReference No: {$registration->id}"
            );
        } catch (\Exception $waEx) {
            Log::error("Failed to send WhatsApp status update: " . $waEx->getMessage());
        }

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Status Update',
            'entity_area' => 'Guest Speaker Registration',
            'description' => "Updated status for Guest Speaker registration ID: {$id} to {$request->status}",
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
        $registration = GuestSpeakerRegistration::find($id);

        if (!$registration) {
            return response()->json([
                'success' => false,
                'message' => 'Guest speaker registration not found'
            ], 404);
        }

        $oldValues = $registration->getAttributes();

        $registration->delete();

        // Create admin audit log
        AuditAdminLogController::createLog([
            'user_id' => Auth::id(),
            'action_type' => 'Delete',
            'entity_area' => 'Guest Speaker Registration',
            'description' => "Deleted Guest Speaker registration ID: {$id}",
            'old_values' => json_encode($oldValues),
            'new_values' => null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Guest speaker registration deleted successfully'
        ]);
    }
}
