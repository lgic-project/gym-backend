<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Handle user login.
     */
    public function login(Request $request)
    {
        // Validate the incoming request data
        $sanitized = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required',
            'user_role' => 'required'
        ]);

        // Attempt to authenticate the user
        if (auth()->attempt($sanitized) && $sanitized['user_role'] !== 1) {
            // Retrieve the user and load related subscriptions
            $user = User::where('email', $sanitized['email'])->firstOrFail();
            $user->load(['subscriptions']);
            $user->token = $user->createToken('API Token')->accessToken;
            
            // Return success response
            return response()->json([
                'status' => 'success',
                'message' => 'User Logged In Successfully',
                'data' => $user,
            ], 200);
        } else {
            // Return error response for invalid credentials
            return response()->json([
                'message' => 'Invalid credentials'
            ], 402);
        }
    }

    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        // Validate the incoming request data
        $sanitized = $request->validate([
            'name' => 'required',
            'email' => ['required', 'unique:users'],
            'password' => ['required', 'min:6', 'confirmed'],
            'gender' => ['nullable'],
            'dob' => ['nullable'],
            'photo' => ['nullable'],
            'user_role' => ['required'],
            'description' => ['nullable'],
        ]);

        // Create the user
        $user = User::create($sanitized);

        // Add photo to the user's media collection if provided
        if ($request->has('photo') && $request->photo !== null) {
            $user->addMedia($sanitized['photo'])->toMediaCollection();
        }

        // Return response based on user creation success
        if ($user) {
            $user->token = $user->createToken('API Token')->accessToken;
            return response()->json([
                'status' => 'success',
                'message' => 'User Registered Successfully',
                'data' => $user,
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'User Registration Failed',
                'data' => [],
            ], 400);
        }
    }

    /**
     * Update user profile.
     */
    public function update(Request $request)
    {
        // Validate the incoming request data
        $sanitized = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
            'gender' => 'nullable',
            'dob' => 'nullable'
        ]);

        // Update the authenticated user's profile
        auth('api')->user()->update($sanitized);

        // Return success response
        return response()->json(['status' => 'success', 'message' => 'Profile is updated successfully']);
    }

    /**
     * Get authenticated user's profile.
     */
    public function profile()
    {
        // Return the authenticated user's profile data
        return response()->json(['data' => auth('api')->user()]);
    }
}
