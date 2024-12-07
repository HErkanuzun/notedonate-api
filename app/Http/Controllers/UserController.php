<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function getProfile($id)
    {
        $user = User::findOrFail($id);
        
        // Get user's content counts
        $notesCount = $user->notes()->count();
        $articlesCount = $user->articles()->count();
        $examsCount = $user->exams()->count();
        
        // Get total stats
        $totalViews = $user->notes()->sum('views') + 
                     $user->articles()->sum('views') + 
                     $user->exams()->sum('views');
                     
        $totalLikes = $user->notes()->sum('likes') + 
                     $user->articles()->sum('likes') + 
                     $user->exams()->sum('likes');
                     
        $totalDownloads = $user->notes()->sum('downloads');
        
        // Get followers and following counts
        $followersCount = $user->followers()->count();
        $followingCount = $user->following()->count();

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'university' => $user->university,
                'department' => $user->department,
                'notes_count' => $notesCount,
                'articles_count' => $articlesCount,
                'exams_count' => $examsCount,
                'total_views' => $totalViews,
                'total_likes' => $totalLikes,
                'total_downloads' => $totalDownloads,
                'followers_count' => $followersCount,
                'following_count' => $followingCount,
                'notes' => $user->notes()->latest()->take(6)->get(),
                'articles' => $user->articles()->latest()->take(6)->get(),
                'exams' => $user->exams()->latest()->take(6)->get(),
            ]
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'bio' => 'sometimes|string|max:500',
            'university' => 'sometimes|string|max:255',
            'department' => 'sometimes|string|max:255',
            'avatar' => 'sometimes|image|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            // Handle avatar upload
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = '/storage/' . $path;
        }

        $user->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }

    public function getStats($id)
    {
        $user = User::findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => [
                'notes_stats' => [
                    'total' => $user->notes()->count(),
                    'views' => $user->notes()->sum('views'),
                    'likes' => $user->notes()->sum('likes'),
                    'downloads' => $user->notes()->sum('downloads')
                ],
                'articles_stats' => [
                    'total' => $user->articles()->count(),
                    'views' => $user->articles()->sum('views'),
                    'likes' => $user->articles()->sum('likes')
                ],
                'exams_stats' => [
                    'total' => $user->exams()->count(),
                    'views' => $user->exams()->sum('views'),
                    'likes' => $user->exams()->sum('likes'),
                    'attempts' => $user->exams()->sum('attempts')
                ]
            ]
        ]);
    }

    public function follow($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        if ($currentUser->id === $user->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'You cannot follow yourself'
            ], 400);
        }

        $currentUser->following()->attach($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'User followed successfully'
        ]);
    }

    public function unfollow($id)
    {
        $user = User::findOrFail($id);
        $currentUser = Auth::user();

        $currentUser->following()->detach($user->id);

        return response()->json([
            'status' => 'success',
            'message' => 'User unfollowed successfully'
        ]);
    }

    public function getFollowers($id)
    {
        $user = User::findOrFail($id);
        $followers = $user->followers()->get();

        return response()->json([
            'status' => 'success',
            'data' => $followers
        ]);
    }

    public function getFollowing($id)
    {
        $user = User::findOrFail($id);
        $following = $user->following()->get();

        return response()->json([
            'status' => 'success',
            'data' => $following
        ]);
    }

    public function profile()
    {
        $user = Auth::user();
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'avatar_url' => $user->avatar_url,
            'bio' => $user->bio,
            'phone' => $user->phone,
            'phone_verified' => $user->phone_verified,
            'followers_count' => $user->followers()->count(),
            'following_count' => $user->following()->count(),
            'total_views' => $user->total_views,
            'total_likes' => $user->total_likes,
            'total_downloads' => $user->total_downloads
        ]);
    }

    public function updateProfileNew(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'bio' => 'nullable|string|max:1000',
            'phone' => ['nullable', 'string', Rule::unique('users')->ignore($user->id)],
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        if ($request->hasFile('avatar')) {
            // Delete old avatar if exists
            if ($user->avatar_url) {
                Storage::delete($user->avatar_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar_url = Storage::url($path);
        }

        $user->name = $request->name;
        $user->email = $request->email;
        $user->bio = $request->bio;
        
        if ($request->phone && $request->phone !== $user->phone) {
            $user->phone = $request->phone;
            $user->phone_verified = false;
            // Generate and send verification code
            $verificationCode = mt_rand(100000, 999999);
            $user->phone_verification_code = $verificationCode;
            // TODO: Implement SMS sending
        }

        $user->save();

        return response()->json([
            'message' => 'Profile updated successfully',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'avatar_url' => $user->avatar_url,
                'bio' => $user->bio,
                'phone' => $user->phone,
                'phone_verified' => $user->phone_verified
            ]
        ]);
    }

    public function verifyPhone(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();

        if ($user->phone_verification_code === $request->code) {
            $user->phone_verified = true;
            $user->phone_verification_code = null;
            $user->save();

            return response()->json([
                'message' => 'Phone number verified successfully'
            ]);
        }

        return response()->json([
            'message' => 'Invalid verification code'
        ], 400);
    }

    public function resendPhoneVerification()
    {
        $user = Auth::user();

        if (!$user->phone) {
            return response()->json([
                'message' => 'No phone number associated with this account'
            ], 400);
        }

        $verificationCode = mt_rand(100000, 999999);
        $user->phone_verification_code = $verificationCode;
        $user->save();

        // TODO: Implement SMS sending
        
        return response()->json([
            'message' => 'Verification code sent successfully'
        ]);
    }
}
