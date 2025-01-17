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
        AuditUserLogController::createLog([
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
            AuditUserLogController::createLog([
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
                    'user_type'=>$user->user_type,
                ],
            ], 200);
        } else {
            return response()->json(['error' => 'Invalid credentials'], 401);
        }
    }





}
