<?php

namespace App\Http\Controllers;

use App\Models\DanaPaymentRequest;
use Illuminate\Http\Request;

class DanaPaymentRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobileNumber' => 'required|string|max:255',
            'wtNumber' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'dana_for_lunch' => 'boolean',
            'dana_for_morning' => 'boolean',
            'date' => 'nullable|string|max:255',
            'ip_address' => 'required|string|max:255',
        ]);

        $danaPaymentRequest = DanaPaymentRequest::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'dana_for_lunch' => $validatedData['dana_for_lunch'],
            'dana_for_morning' => $validatedData['dana_for_morning'],
            'dana_event_date' => $validatedData['date'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $danaPaymentRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Dana Payment Request',
            'old_values' => null, // No old values for a new submission
            'new_values' => $danaPaymentRequest,
            'description' => $validatedData['firstName'] . " submitted a Dana Payment Request. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Dana Payment request submitted successfully', 'data' => $danaPaymentRequest], 201);
    }
}
