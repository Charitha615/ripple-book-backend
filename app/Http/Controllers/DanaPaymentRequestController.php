<?php

namespace App\Http\Controllers;

use App\Mail\FormNotificationMail;
use App\Models\DanaPaymentRequest;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class DanaPaymentRequestController extends Controller
{
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'firstName' => 'required|string|max:255',
                'lastName' => 'required|string|max:255',
                'mobileNumber' => 'required|string|max:255',
                'wtNumber' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_for_lunch' => 'boolean',
                'dana_for_morning' => 'boolean',
                'date' => 'nullable|string|max:255',
                'ip_address' => 'required|string|max:255',
            ]);

            $danaPaymentRequest = DanaPaymentRequest::create([
                'first_name' => $validatedData['firstName'],
                'last_name' => $validatedData['lastName'],
                'mobile_number' => $validatedData['mobileNumber'],
                'wt_number' => $validatedData['wtNumber'] ?? null,
                'email' => $validatedData['email'],
                'dana_for_lunch' => $validatedData['dana_for_lunch'] ?? false,
                'dana_for_morning' => $validatedData['dana_for_morning'] ?? false,
                'dana_event_date' => $validatedData['date'] ?? null,
                'ip_address' => $validatedData['ip_address'],
            ]);

            try {
                UserLogController::createLog([
                    'user_id' => null,
                    'form_id' => $danaPaymentRequest->id,
                    'action_type' => 'form_submission',
                    'entity_area' => 'Dana Payment Request',
                    'old_values' => null,
                    'new_values' => $danaPaymentRequest,
                    'description' => $validatedData['firstName'] . " submitted a Dana Payment Request. Mobile number is " . $validatedData['mobileNumber'],
                ]);
            } catch (\Exception $logException) {
                Log::error('Failed to create user log for Dana Payment request: ' . $logException->getMessage(), [
                    'exception' => $logException,
                    'request_data' => $validatedData
                ]);
            }

            try {
                Mail::to($validatedData['email'])->send(
                    new FormNotificationMail(
                        "Dana Payment Request Submitted - Ref #{$danaPaymentRequest->id}",
                        "Dear {$validatedData['firstName']},\n\nYour Dana Payment Request has been received successfully.\nReference No: {$danaPaymentRequest->id}\nWe will review it shortly."
                    )
                );
            } catch (\Exception $mailEx) {
                Log::error("Failed to send email: " . $mailEx->getMessage());
            }

            try {
                WhatsAppService::sendMessage(
                    $validatedData['mobileNumber'],
                    "Hello {$validatedData['firstName']}, your Dana Payment Request has been received.\nReference No: {$danaPaymentRequest->id}"
                );
            } catch (\Exception $waEx) {
                Log::error("Failed to send WhatsApp message: " . $waEx->getMessage());
            }


            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request submitted successfully',
                'data' => $danaPaymentRequest
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $validationException) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validationException->errors()
            ], 422);

        } catch (\Illuminate\Database\QueryException $queryException) {
            Log::error('Database error in DanaPaymentRequest store: ' . $queryException->getMessage(), [
                'exception' => $queryException,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Database error occurred while processing your request',
                'error' => 'Please try again later'
            ], 500);

        } catch (\Exception $exception) {
            Log::error('Unexpected error in DanaPaymentRequest store: ' . $exception->getMessage(), [
                'exception' => $exception,
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred',
                'error' => 'Please try again later'
            ], 500);
        }
    }

    public function getById($id)
    {
        try {
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $danaPaymentRequest
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana Payment request'
            ], 500);
        }
    }

    public function edit(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaPaymentRequest->getAttributes();

            // Validate the request data using snake_case keys
            $validator = Validator::make($request->all(), [
                'first_name' => 'required|string|max:255',
                'last_name' => 'required|string|max:255',
                'mobile_number' => 'required|string|max:255',
                'wt_number' => 'nullable|string|max:255',
                'email' => 'required|email|max:255',
                'dana_for_lunch' => 'boolean',
                'dana_for_morning' => 'boolean',
                'dana_event_date' => 'nullable|string|max:255', // Changed from 'date' to match DB column
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the Dana Payment request with consistent field names
            $danaPaymentRequest->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile_number' => $request->mobile_number,
                'wt_number' => $request->wt_number ?? null,
                'email' => $request->email,
                'dana_for_lunch' => $request->dana_for_lunch ?? false,
                'dana_for_morning' => $request->dana_for_morning ?? false,
                'dana_event_date' => $request->dana_event_date ?? null, // Changed from 'date'
            ]);

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Update',
                'entity_area' => 'Dana Payment Request',
                'description' => "Updated Dana Payment request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($danaPaymentRequest->fresh()->getAttributes()),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request updated successfully',
                'data' => $danaPaymentRequest
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error updating Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update Dana Payment request'
            ], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            // Get paginated results (default 15 per page)
            $perPage = $request->input('per_page', 15);
            $danaPaymentRequests = DanaPaymentRequest::paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $danaPaymentRequests
            ], 200);

        } catch (\Exception $exception) {
            Log::error('Error fetching Dana Payment requests: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch Dana Payment requests'
            ], 500);
        }
    }

    public function softDelete($id)
    {
        DB::beginTransaction();
        try {
            $danaPaymentRequest = DanaPaymentRequest::find($id);

            if (!$danaPaymentRequest) {
                return response()->json([
                    'success' => false,
                    'message' => 'Dana Payment request not found'
                ], 404);
            }

            // Capture old values for audit log
            $oldValues = $danaPaymentRequest->getAttributes();

            // Soft delete the Dana Payment request
            $danaPaymentRequest->delete();

            // Create admin audit log
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Delete',
                'entity_area' => 'Dana Payment Request',
                'description' => "Soft deleted Dana Payment request ID: {$id}",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode(['deleted_at' => now()]),
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Dana Payment request soft deleted successfully'
            ], 200);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Error soft deleting Dana Payment request: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to soft delete Dana Payment request'
            ], 500);
        }
    }


    public function updateStatus(Request $request, $id)
    {
        try {
            $Request = DanaPaymentRequest::find($id);

            if (!$Request) {
                return response()->json([
                    'success' => false,
                    'message' => 'DanaPaymentRequest request not found'
                ], 404);
            }

            // Validate input
            $validator = Validator::make($request->all(), [
                'status' => 'required|in:Pending,Approved,Rejected,On hold',
                'status_reason' => 'nullable|string|max:1000',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $status = $request->input('status');
            $reason = $request->input('status_reason');

            // Require reason for Approved, Rejected, On hold
            if (in_array($status, ['Approved', 'Rejected', 'On hold']) && empty($reason)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status reason is required when status is Approved, Rejected, or On hold'
                ], 422);
            }

            // Capture old values before updating
            $oldValues = $Request->getAttributes();

            // Update the status
            $Request->update([
                'status' => $status,
                'status_reason' => $reason
            ]);

            // Capture new values after updating
            $newValues = $Request->fresh()->getAttributes();

            // Log to AuditAdminLog
            AuditAdminLogController::createLog([
                'user_id' => Auth::id(),
                'action_type' => 'Status Change',
                'entity_area' => 'Dana Payment Request',
                'description' => "Changed status of Dana Payment Request request ID: {$id} to '{$status}'",
                'old_values' => json_encode($oldValues),
                'new_values' => json_encode($newValues),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully',
                'data' => $Request
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating Dana Payment Request request status: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status',
                'error' => 'Please try again later'
            ], 500);
        }
    }
}
