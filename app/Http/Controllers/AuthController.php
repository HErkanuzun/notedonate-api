<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class AuthController extends Controller
{
    
    public function register(Request $request) {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
    
        return response()->json([
           "message" => "User registered successfully",
        ]);
    } 

    //login with sanctum and return token
    public function login(Request $request) {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if (auth()->attempt($credentials)) {
            $user = auth()->user();
            $token = $user->createToken('auth_token', ['*'], now()->addMinutes(30))->plainTextToken;
    
            return response()->json([
                "message" => "User logged in successfully",
                "token" => $token,
            ]);
        }
    
        return response()->json([
            "message" => "Invalid credentials",
        ], 401);
    }

    public function logout(Request $request) {
        $request->user()->currentAccessToken()->delete();
        return response()->json([
            "message" => "User logged out successfully",
        ]);
    }
}
