<?php

namespace App\Http\Controllers;

use App\Models\SoloRetreatRegistration;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class SoloRetreatRegistrationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            $registrations = SoloRetreatRegistration::orderBy('created_at', 'desc')->get();
            return response()->json([
                'success' => true,
                'data' => $registrations
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching retreat registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch registrations'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'date_of_birth' => 'required|date',
            'age' => 'required|integer|min:18',
            'email_address' => 'required|email|unique:solo_retreat_registrations,email_address',
            'mobile_number' => 'nullable|string|max:20',
            'whatsapp_number' => 'nullable|string|max:20',
            'arrival_date' => 'nullable|date',
            'departure_date' => 'nullable|date|after_or_equal:arrival_date',
            'emergency_mobile_number_1' => 'nullable|string|max:20',
            'emergency_mobile_number_2' => 'nullable|string|max:20',
            'pdf_upload' => 'nullable|string', // base64 string
            'sign_full_name' => 'nullable|string|max:255',
            'sign_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $data = $request->all();
            $data['ip_address'] = $request->ip();

            // Handle PDF upload (if exists)
            if (!empty($request->pdf_upload)) {
                $pdfData = $request->pdf_upload;

                // Remove "data:application/pdf;base64," if present
                if (strpos($pdfData, 'base64,') !== false) {
                    $pdfData = explode('base64,', $pdfData)[1];
                }

                $pdfData = base64_decode($pdfData);
                $fileName = 'solo_retreat_' . time() . '.pdf';
                $filePath = 'uploads/retreat_pdfs/' . $fileName;

                // Store file in storage/app/public/uploads/retreat_pdfs
                Storage::disk('public')->put($filePath, $pdfData);

                // Save file path in DB instead of base64
                $data['pdf_upload'] = $filePath;
            }

            $registration = SoloRetreatRegistration::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Registration submitted successfully',
                'data' => $registration
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create registration'
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $registration = SoloRetreatRegistration::findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $registration
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration not found'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        try {
            $registration = SoloRetreatRegistration::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'date_of_birth' => 'sometimes|required|date',
                'age' => 'sometimes|required|integer|min:18',
                'email_address' => 'sometimes|required|email|unique:solo_retreat_registrations,email_address,' . $id,
                'departure_date' => 'nullable|date|after_or_equal:arrival_date',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            $registration->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Registration updated successfully',
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update registration'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $registration = SoloRetreatRegistration::findOrFail($id);
            $registration->delete();

            return response()->json([
                'success' => true,
                'message' => 'Registration deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting retreat registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete registration'
            ], 500);
        }
    }

    /**
     * Update registration status
     */
    public function updateStatus(Request $request, string $id): JsonResponse
    {
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

        try {
            $registration = SoloRetreatRegistration::findOrFail($id);
            $registration->update([
                'status' => $request->status,
                'status_reason' => $request->status_reason
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $registration
            ]);

        } catch (\Exception $e) {
            Log::error('Error updating registration status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status'
            ], 500);
        }
    }

    /**
     * Get registrations by status
     */
    public function getByStatus(string $status): JsonResponse
    {
        $validStatuses = ['Pending', 'Approved', 'Rejected', 'On hold'];

        if (!in_array($status, $validStatuses)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid status'
            ], 422);
        }

        try {
            $registrations = SoloRetreatRegistration::where('status', $status)
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $registrations
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching registrations by status: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch registrations'
            ], 500);
        }
    }
}
