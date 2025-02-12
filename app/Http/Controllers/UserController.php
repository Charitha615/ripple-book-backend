<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;



class UserController extends Controller
{
    public function getAllUsers()
    {
        $users = User::all(); // Directly fetch all users

        return response()->json(['users' => $users], 200);
    }


    public function createUser(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'fullName' => 'required|string|max:191',
            'gender' => 'required|string|max:10',
            'nic' => 'required|string|max:191|unique:users,nic',
            'role' => 'required|string|in:User,Coordinator',
            'is_events_coordinator' => 'nullable|boolean',
            'is_community_service_coordinator' => 'nullable|boolean',
            'is_dana_coordinator' => 'nullable|boolean',
            'is_meditate_with_us_coordinator' => 'nullable|boolean',
            'is_dhamma_talks_coordinator' => 'nullable|boolean',
            'is_arama_poojawa_coordinator' => 'nullable|boolean',
            'is_build_up_hermitage_coordinator' => 'nullable|boolean',
            'is_donation_coordinator' => 'nullable|boolean',
        ]);

        // Return validation errors if any
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        DB::beginTransaction();
        try {
            // Auto-generate a password
            $password = Str::random(12); // Increased length for security
            $hashedPassword = Hash::make($password);

            // Set coordinator flags
            $is_events_coordinator = $request->is_events_coordinator ?? 0;
            $is_community_service_coordinator = $request->is_community_service_coordinator ?? 0;
            $is_dana_coordinator = $request->is_dana_coordinator ?? 0;
            $is_meditate_with_us_coordinator = $request->is_meditate_with_us_coordinator ?? 0;
            $is_dhamma_talks_coordinator = $request->is_dhamma_talks_coordinator ?? 0;
            $is_arama_poojawa_coordinator = $request->is_arama_poojawa_coordinator ?? 0;
            $is_build_up_hermitage_coordinator = $request->is_build_up_hermitage_coordinator ?? 0;
            $is_donation_coordinator = $request->is_donation_coordinator ?? 0;

            if ($request->role === "User") {
                $is_events_coordinator = 0;
                $is_community_service_coordinator = 0;
                $is_dana_coordinator = 0;
                $is_meditate_with_us_coordinator = 0;
                $is_dhamma_talks_coordinator = 0;
                $is_arama_poojawa_coordinator = 0;
                $is_build_up_hermitage_coordinator = 0;
                $is_donation_coordinator = 0;
            }

            // Create user
            $user = User::create([
                'name' => $request->fullName,
                'email' => $request->email,
                'password' => $hashedPassword,
                'user_type' => $request->role,
                'gender' => $request->gender,
                'nic' => $request->nic,
                'is_events_coordinator' => $is_events_coordinator,
                'is_community_service_coordinator' => $is_community_service_coordinator,
                'is_dana_coordinator' => $is_dana_coordinator,
                'is_meditate_with_us_coordinator' => $is_meditate_with_us_coordinator,
                'is_dhamma_talks_coordinator' => $is_dhamma_talks_coordinator,
                'is_arama_poojawa_coordinator' => $is_arama_poojawa_coordinator,
                'is_build_up_hermitage_coordinator' => $is_build_up_hermitage_coordinator,
                'is_donation_coordinator' => $is_donation_coordinator,
            ]);

            // Send email with password
            try {
                Mail::raw("Your account has been created. Your password is: $password", function ($message) use ($user) {
                    $message->to($user->email)
                        ->subject('Your New Account Credentials');
                });
            } catch (\Exception $emailException) {
                // Log email sending error
                Log::error('Failed to send email: ' . $emailException->getMessage());

                return response()->json([
                    'success' => false,
                    'message' => 'User created successfully, but failed to send email with credentials.',
                    'user' => $user,
                ], 201);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User created successfully and password sent via email.',
                'user' => $user,
            ], 201);

        } catch (\Exception $exception) {
            DB::rollBack();
            Log::error('Failed to create user: ' . $exception->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the user.',
                'error' => $exception->getMessage(),
            ], 500);
        }
    }


}
