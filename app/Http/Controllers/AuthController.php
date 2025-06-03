<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'user_type' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'user_type' => $request->user_type,
            'password' => Hash::make($request->password),
        ]);

        // Generate token
        $token = $user->createToken('LaravelAuthApp')->plainTextToken;

        // Log the user creation
        AuditAdminLogController::createLog([
            'user_id' => $user->id,
            'action_type' => 'Register',
            'entity_area' => 'User Management',
            'description' => "New user created with name: {$user->name} and ID: {$user->id} UserType: {$user->user_type}.",
            'new_values' => json_encode([
                'name' => $user->name,
                'email' => $user->email,
                'user_type' => $user->user_type,
            ]),
        ]);

        // Return success response
        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                ],
                'token' => $token,
            ],
        ], 201);
    }


    public function login(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        // Attempt authentication
        $credentials = $request->only('email', 'password');
        if (auth()->attempt($credentials)) {
            $user = auth()->user();

            // Revoke all existing tokens for the user
            $user->tokens()->delete();

            // Generate a new token
            $token = $user->createToken('LaravelAuthApp')->plainTextToken;

            // Log the successful login attempt
            AuditAdminLogController::createLog([
                'user_id' => $user->id,
                'action_type' => 'Login',
                'entity_area' => 'User Management',
                'description' => "User with ID {$user->id}, named {$user->name}, User Role ({$user->user_type}), logged in successfully.",
                'new_values' => json_encode([
                    'user_id' => $user->id,
                    'email' => $user->email,
                ]),
            ]);

            return response()->json([
                'message' => 'Login successful',
                'token' => $token,
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'user_type' => $user->user_type,
                    'gender' => $user->gender,
                    'nic' => $user->nic,
                    'account_created_date' => $user->created_at,
                ],
                'access_area' => [
                    'is_events_coordinator' => $user->is_events_coordinator,
                    'is_community_service_coordinator' => $user->is_community_service_coordinator,
                    'is_dana_coordinator' => $user->is_dana_coordinator,
                    'is_meditate_with_us_coordinator' => $user->is_meditate_with_us_coordinator,
                    'is_dhamma_talks_coordinator' => $user->is_dhamma_talks_coordinator,
                    'is_arama_poojawa_coordinator' => $user->is_arama_poojawa_coordinator,
                    'is_build_up_hermitage_coordinator' => $user->is_build_up_hermitage_coordinator,
                    'is_donation_coordinator' => $user->is_donation_coordinator,
                ],
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }

    public function logout(Request $request)
    {
        // Revoke the user's current access token
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout successful'
        ], 200);
    }


}
