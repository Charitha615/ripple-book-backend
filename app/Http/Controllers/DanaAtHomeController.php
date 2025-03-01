<?php

namespace App\Http\Controllers;

use App\Models\DanaAtHome;
use Illuminate\Http\Request;

class DanaAtHomeController extends Controller
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
            'specific_event' => 'nullable|string|max:255',
            'other' => 'nullable|string|max:255',
            'birthday' => 'boolean',
            'sevenday' => 'boolean',
            'warming' => 'boolean',
            'threemonths' => 'boolean',
            'oneyear' => 'boolean',
            'annually' => 'boolean',
            'weddings' => 'boolean',
            'ip_address' => 'required|string|max:255',
        ]);

        $danaAtHomeRequest = DanaAtHome::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'dana_for_lunch' => $validatedData['dana_for_lunch'],
            'dana_for_morning' => $validatedData['dana_for_morning'],
            'specific_event' => $validatedData['specific_event'],
            'other' => $validatedData['other'],
            'birthday' => $validatedData['birthday'],
            'sevenday' => $validatedData['sevenday'],
            'warming' => $validatedData['warming'],
            'threemonths' => $validatedData['threemonths'],
            'oneyear' => $validatedData['oneyear'],
            'annually' => $validatedData['annually'],
            'weddings' => $validatedData['weddings'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $danaAtHomeRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Dana At Home Request',
            'old_values' => null, // No old values for a new submission
            'new_values' => $danaAtHomeRequest,
            'description' => $validatedData['firstName'] . " submitted a Dana At Home Request. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Sermon request submitted successfully', 'data' => $danaAtHomeRequest], 201);
    }
}
