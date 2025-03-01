<?php

namespace App\Http\Controllers;

use App\Models\DanaRequest;
use Illuminate\Http\Request;

class DanaRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobileNumber' => 'required|string|max:255',
            'wtNumber' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'date' => 'nullable|string|max:255',
            'ip_address' => 'required|string|max:255',
        ]);

        $danaRequest = DanaRequest::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'dana_event_date' => $validatedData['date'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $danaRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Dana Request',
            'old_values' => null, // No old values for a new submission
            'new_values' => $danaRequest,
            'description' => $validatedData['firstName'] . " submitted a Dana Request. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Dana request submitted successfully', 'data' => $danaRequest], 201);
    }
}
