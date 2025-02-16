<?php

namespace App\Http\Controllers;

use App\Models\SermonRequest;
use Illuminate\Http\Request;

class SermonRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'mobileNumber' => 'required|string|max:255',
            'wtNumber' => 'nullable|string|max:255',
            'email' => 'required|email|max:255',
            'date' => 'required|string|max:255',
            'time' => 'required|string|max:255',
            'count' => 'nullable|string|max:255',
            'option' => 'nullable|string|max:255',
            'birthday' => 'boolean',
            'sevenday' => 'boolean',
            'warming' => 'boolean',
            'threemonths' => 'boolean',
            'oneyear' => 'boolean',
            'annually' => 'boolean',
            'weddings' => 'boolean',
            'ip_address' => 'required|string|max:255',
        ]);

        $sermonRequest = SermonRequest::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'mobile_number' => $validatedData['mobileNumber'],
            'wt_number' => $validatedData['wtNumber'],
            'email' => $validatedData['email'],
            'date' => $validatedData['date'],
            'time' => $validatedData['time'],
            'count' => $validatedData['count'],
            'option' => $validatedData['option'],
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
            'form_id' => $sermonRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Damma Sermons Request',
            'old_values' => null, // No old values for a new submission
            'new_values' => $sermonRequest,
            'description' => $validatedData['firstName'] . " submitted a sermon request. Mobile number is " . $validatedData['mobileNumber'],
        ]);

        return response()->json(['message' => 'Sermon request submitted successfully', 'data' => $sermonRequest], 201);
    }
}
