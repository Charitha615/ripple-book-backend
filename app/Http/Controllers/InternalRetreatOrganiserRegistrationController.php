<?php

namespace App\Http\Controllers;

use App\Models\InternalRetreatOrganiserRegistration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class InternalRetreatOrganiserRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $registrations = InternalRetreatOrganiserRegistration::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $registrations,
                'message' => 'Registrations retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve registrations: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'date_of_birth' => 'required|date',
                'age' => 'required|integer|min:1',
                'gender' => 'required|in:Male,Female,Other',
                'address' => 'required|string',
                'city' => 'required|string|max:255',
                'postal_code' => 'required|string|max:20',
                'mobile_number' => 'required|string|max:20',
                'whatsapp_number' => 'required|string|max:20',
                'email_address' => 'required|email|max:255',
                'emergency_first_name' => 'required|string|max:255',
                'emergency_last_name' => 'required|string|max:255',
                'emergency_relationship' => 'required|string|max:255',
                'emergency_mobile_number_1' => 'required|string|max:20',
                'emergency_mobile_number_2' => 'nullable|string|max:20',
                'beginner' => 'boolean',
                'experienced_volunteer' => 'boolean',
                'months_experience' => 'boolean',
                'years_experience' => 'boolean',
                'months' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'ip_address' => 'nullable|ip'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $registration = InternalRetreatOrganiserRegistration::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'date_of_birth' => $request->date_of_birth,
                'age' => $request->age,
                'gender' => $request->gender,
                'address' => $request->address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'mobile_number' => $request->mobile_number,
                'whatsapp_number' => $request->whatsapp_number,
                'email_address' => $request->email_address,
                'emergency_first_name' => $request->emergency_first_name,
                'emergency_last_name' => $request->emergency_last_name,
                'emergency_relationship' => $request->emergency_relationship,
                'emergency_mobile_number_1' => $request->emergency_mobile_number_1,
                'emergency_mobile_number_2' => $request->emergency_mobile_number_2,
                'beginner' => $request->beginner ?? false,
                'experienced_volunteer' => $request->experienced_volunteer ?? false,
                'months_experience' => $request->months_experience ?? false,
                'years_experience' => $request->years_experience ?? false,
                'months' => $request->months,
                'description' => $request->description,
                'ip_address' => $request->ip_address ?? request()->ip(),
                'status' => 'Pending'
            ]);

            return response()->json([
                'success' => true,
                'data' => $registration,
                'message' => 'Registration created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $registration = InternalRetreatOrganiserRegistration::find($id);

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $registration,
                'message' => 'Registration retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $registration = InternalRetreatOrganiserRegistration::find($id);

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'date_of_birth' => 'sometimes|required|date',
                'age' => 'sometimes|required|integer|min:1',
                'gender' => 'sometimes|required|in:Male,Female,Other',
                'address' => 'sometimes|required|string',
                'city' => 'sometimes|required|string|max:255',
                'postal_code' => 'sometimes|required|string|max:20',
                'mobile_number' => 'sometimes|required|string|max:20',
                'whatsapp_number' => 'sometimes|required|string|max:20',
                'email_address' => 'sometimes|required|email|max:255',
                'emergency_first_name' => 'sometimes|required|string|max:255',
                'emergency_last_name' => 'sometimes|required|string|max:255',
                'emergency_relationship' => 'sometimes|required|string|max:255',
                'emergency_mobile_number_1' => 'sometimes|required|string|max:20',
                'emergency_mobile_number_2' => 'nullable|string|max:20',
                'beginner' => 'sometimes|boolean',
                'experienced_volunteer' => 'sometimes|boolean',
                'months_experience' => 'sometimes|boolean',
                'years_experience' => 'sometimes|boolean',
                'months' => 'nullable|integer|min:0',
                'description' => 'nullable|string',
                'status' => 'sometimes|required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string',
                'ip_address' => 'nullable|ip'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $registration->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $registration,
                'message' => 'Registration updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $registration = InternalRetreatOrganiserRegistration::find($id);

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found'
                ], 404);
            }

            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete registration: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status of the registration
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $registration = InternalRetreatOrganiserRegistration::find($id);

            if (!$registration) {
                return response()->json([
                    'success' => false,
                    'message' => 'Registration not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $registration->update([
                'status' => $request->status,
                'status_reason' => $request->status_reason
            ]);

            return response()->json([
                'success' => true,
                'data' => $registration,
                'message' => 'Registration status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update registration status: ' . $e->getMessage()
            ], 500);
        }
    }
}
