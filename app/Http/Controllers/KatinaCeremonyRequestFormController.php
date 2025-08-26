<?php

namespace App\Http\Controllers;

use App\Models\KatinaCeremonyRequestForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KatinaCeremonyRequestFormController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $forms = KatinaCeremonyRequestForm::orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $forms,
                'message' => 'Forms retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve forms: ' . $e->getMessage()
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
                'mobile_number' => 'required|string|max:20',
                'wt_number' => 'required|string|max:50',
                'email' => 'required|email|max:255',
                'year' => 'required|string|max:4',
                'ip_address' => 'nullable|ip'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }

            $form = KatinaCeremonyRequestForm::create([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'wt_number' => $request->wt_number,
                'email' => $request->email,
                'year' => $request->year,
                'ip_address' => $request->ip_address ?? request()->ip(),
                'status' => 'Pending'
            ]);

            return response()->json([
                'success' => true,
                'data' => $form,
                'message' => 'Form created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $form = KatinaCeremonyRequestForm::find($id);

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $form,
                'message' => 'Form retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $form = KatinaCeremonyRequestForm::find($id);

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'mobile_number' => 'sometimes|required|string|max:20',
                'wt_number' => 'sometimes|required|string|max:50',
                'email' => 'sometimes|required|email|max:255',
                'year' => 'sometimes|required|string|max:4',
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

            $form->update($request->all());

            return response()->json([
                'success' => true,
                'data' => $form,
                'message' => 'Form updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $form = KatinaCeremonyRequestForm::find($id);

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found'
                ], 404);
            }

            $form->delete();

            return response()->json([
                'success' => true,
                'message' => 'Form deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete form: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update status of the form
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $form = KatinaCeremonyRequestForm::find($id);

            if (!$form) {
                return response()->json([
                    'success' => false,
                    'message' => 'Form not found'
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

            $form->update([
                'status' => $request->status,
                'status_reason' => $request->status_reason
            ]);

            return response()->json([
                'success' => true,
                'data' => $form,
                'message' => 'Form status updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form status: ' . $e->getMessage()
            ], 500);
        }
    }
}
