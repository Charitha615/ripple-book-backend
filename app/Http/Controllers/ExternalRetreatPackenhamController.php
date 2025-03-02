<?php

namespace App\Http\Controllers;

use App\Models\ExternalRetreatPackenham;
use Illuminate\Http\Request;

class ExternalRetreatPackenhamController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobileNumber' => 'required|string|max:255',
            'wtNumber' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'number_of_people' => 'nullable|integer',
            'ip_address' => 'required|string|max:255',
        ]);

        $ExternalRetreatPackenham =ExternalRetreatPackenham::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'number_of_people' => $validatedData['number_of_people'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $ExternalRetreatPackenham->id,
            'action_type' => 'form_submission',
            'entity_area' => 'External Retreat Request Form Packenham',
            'old_values' => null, // No old values for a new submission
            'new_values' => $ExternalRetreatPackenham,
            'description' => $validatedData['firstName'] . " submitted a External Retreat Request Form Packenham. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Form request submitted successfully', 'data' => $ExternalRetreatPackenham], 201);
    }
}
