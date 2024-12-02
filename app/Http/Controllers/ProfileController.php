<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;

class ProfileController extends Controller
{
    /**
     * Get authenticated user's profile
     */
    public function show($id)
    {
        try {
            $user = User::with(['role', 'profile'])
                ->findOrFail($id);

            $data = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'address' => $user->address,
                'profile_photo' => $user->profile_photo,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name
                ] : null,
                'profile' => $user->profile ? [
                    'bio' => $user->profile->bio,
                    'education' => $user->profile->education,
                    'interests' => $user->profile->interests,
                    'social_media' => $user->profile->social_media
                ] : null,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at
            ];

            return response()->json([
                'status' => true,
                'message' => 'Profile retrieved successfully',
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving profile',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
