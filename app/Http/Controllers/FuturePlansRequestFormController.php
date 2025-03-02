<?php

namespace App\Http\Controllers;

use App\Models\FuturePlansRequestForm;
use Illuminate\Http\Request;

class FuturePlansRequestFormController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'mobile_number' => 'nullable|string|max:255',
            'wt_number' => 'nullable|string|max:255',
            'email' => 'nullable|string|max:255',
            'contribute' => 'required|string|max:255',
            'inquire' => 'nullable|string|max:255',
            'ip_address' => 'required|string|max:255',
        ]);

        $FuturePlansRequestForm =FuturePlansRequestForm::create([
            'first_name' => $validatedData['firstName'],
            'last_name' => $validatedData['lastName'],
            'address' => $validatedData['address'],
            'postal_code' => $validatedData['postal_code'],
            'city' => $validatedData['city'],
            'mobile_number' => $validatedData['mobile_number'],
            'wt_number' => $validatedData['wt_number'],
            'email' => $validatedData['email'],
            'contribute' => $validatedData['contribute'],
            'inquire' => $validatedData['inquire'],
            'ip_address' => $validatedData['ip_address'],
        ]);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $FuturePlansRequestForm->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Future Plans Request Form',
            'old_values' => null, // No old values for a new submission
            'new_values' => $FuturePlansRequestForm,
            'description' => $validatedData['firstName'] . " submitted a Future Plans Request Form. Mobile number is " . $validatedData['mobile_number'],
        ]);

        return response()->json(['message' => 'Form request submitted successfully', 'data' => $FuturePlansRequestForm], 201);
    }
}
