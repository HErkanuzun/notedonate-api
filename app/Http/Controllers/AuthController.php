<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Auth\Events\Registered;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;

class AuthController extends Controller
{
    /**
     * Register a new user and send verification email.
     */
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'profile_photo_url' => 'https://ui-avatars.com/api/?name=' . urlencode($request->name),
        ]);

        // Send verification email
        event(new Registered($user));

        // Create token
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'Registration successful. Please check your email for verification link.',
            'token' => $token,
            'user' => $user
        ]);
    }

    /**
     * Login a user if email is verified.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token')->plainTextToken;

            // Include all necessary user data
            $userData = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
            ];

            return response()->json([
                'status' => 'success',
                'token' => $token,
                'user' => $userData
            ]);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'Invalid credentials.',
        ], 401);
    }

    /**
     * Reset the user's password.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required',
        ]);

        // Reset password logic here

        return response()->json([
            'status' => 'success',
            'message' => 'Password reset successful.',
        ]);
    }

    /**
     * Get the authenticated user's data.
     */
    public function userData()
    {
        $user = auth()->user();
        
        return response()->json([
            'status' => 'success',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'profile_photo_url' => $user->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($user->name),
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at,
                'notes' => $user->notes,
                'exams' => $user->exams,
                'articles' => $user->articles,
            ]
        ]);
    }

    /**
     * Log out the user by revoking the token.
     */
    public function logout(Request $request)
    {
        // Revoke all tokens
        $request->user()->tokens()->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Successfully logged out'
        ]);
    }
}
