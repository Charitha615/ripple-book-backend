<?php

namespace App\Http\Controllers;

use App\Models\ExternalRetreatHallam;
use Illuminate\Http\Request;

class ExternalRetreatHallamController extends Controller
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

        $ExternalRetreatHallam =ExternalRetreatHallam::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'date' => $validatedData['date'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $ExternalRetreatHallam->id,
            'action_type' => 'form_submission',
            'entity_area' => 'External Retreat Request Form Hallam',
            'old_values' => null, // No old values for a new submission
            'new_values' => $ExternalRetreatHallam,
            'description' => $validatedData['firstName'] . " submitted a External Retreat Request Form Hallam. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Form request submitted successfully', 'data' => $ExternalRetreatHallam], 201);
    }
}
