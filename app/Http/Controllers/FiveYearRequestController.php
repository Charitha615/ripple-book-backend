<?php

namespace App\Http\Controllers;

use App\Models\FiveYearRequest;
use Illuminate\Http\Request;

class FiveYearRequestController extends Controller
{
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|string|max:255',
            'gender' => 'required|string|max:255',
            'street_address_line_1' => 'required|string|max:255',
            'street_address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:255',
            'postal_code' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
            'mobile_number' => 'required|string|max:255',
            'wt_number' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            '5_land_plots' => 'boolean',
            '10_land_plots' => 'boolean',
            '20_land_plots' => 'boolean',
            '50_land_plots' => 'boolean',
            'query' => 'nullable|string|max:1000',
            'ip_address' => 'required|string|max:45',
        ]);

        $fiveYearRequest = FiveYearRequest::create($validatedData);

        // Log the user activity
        UserLogController::createLog([
            'user_id' => null, // No user ID for public forms
            'form_id' => $fiveYearRequest->id,
            'action_type' => 'form_submission',
            'entity_area' => 'Five Year Request Form',
            'old_values' => null, // No old values for a new submission
            'new_values' => $fiveYearRequest,
            'description' => $validatedData['first_name'] . " submitted a Five Year Request Form. Mobile number is " . $validatedData['mobile_number'],
        ]);

        return response()->json(['message' => 'Form request submitted successfully', 'data' => $fiveYearRequest], 201);
    }
}
