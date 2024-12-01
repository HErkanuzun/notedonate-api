<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\VerificationCode;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PhoneVerificationController extends Controller
{
    protected $twilioService;

    public function __construct(TwilioService $twilioService)
    {
        $this->twilioService = $twilioService;
    }

    public function sendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15'
        ]);

        $user = auth()->user();

        // Check if phone number is already verified
        if ($user->phone_verified_at && $user->phone === $request->phone) {
            return response()->json([
                'message' => 'Phone number is already verified'
            ], 422);
        }

        // Check if phone number is used by another user
        $existingUser = User::where('phone', $request->phone)
            ->where('id', '!=', $user->id)
            ->where('phone_verified_at', '!=', null)
            ->first();

        if ($existingUser) {
            return response()->json([
                'message' => 'Phone number is already used by another user'
            ], 422);
        }

        // Eski kodları temizle
        VerificationCode::where('phone', $request->phone)
            ->where('used', false)
            ->delete();

        // Yeni kod oluştur
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Kodu veritabanına kaydet
        VerificationCode::create([
            'phone' => $request->phone,
            'code' => $code,
            'expires_at' => Carbon::now()->addMinutes(10)
        ]);

        // SMS gönder
        $message = "Your NoteApp verification code is: " . $code;
        $sent = $this->twilioService->sendSMS($request->phone, $message);

        if (!$sent) {
            return response()->json([
                'message' => 'Failed to send verification code'
            ], 500);
        }

        return response()->json([
            'message' => 'Verification code sent successfully'
        ]);
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15',
            'code' => 'required|string|size:6'
        ]);

        $user = auth()->user();

        $verificationCode = VerificationCode::where('phone', $request->phone)
            ->where('code', $request->code)
            ->where('used', false)
            ->latest()
            ->first();

        if (!$verificationCode) {
            return response()->json([
                'message' => 'Invalid verification code'
            ], 422);
        }

        if (!$verificationCode->isValid()) {
            return response()->json([
                'message' => 'Verification code has expired'
            ], 422);
        }

        // Kodu kullanıldı olarak işaretle
        $verificationCode->used = true;
        $verificationCode->save();

        // Kullanıcının telefon numarasını güncelle ve doğrulanmış olarak işaretle
        $user->phone = $request->phone;
        $user->phone_verified_at = now();
        $user->save();

        return response()->json([
            'message' => 'Phone number verified successfully',
            'user' => $user
        ]);
    }

    public function resendCode(Request $request)
    {
        $request->validate([
            'phone' => 'required|string|min:10|max:15'
        ]);

        $lastCode = VerificationCode::where('phone', $request->phone)
            ->latest()
            ->first();

        if ($lastCode && $lastCode->created_at->gt(now()->subMinutes(2))) {
            return response()->json([
                'message' => 'Please wait before requesting a new code'
            ], 429);
        }

        return $this->sendCode($request);
    }

    public function getStatus()
    {
        $user = auth()->user();
        
        return response()->json([
            'phone' => $user->phone,
            'verified' => !is_null($user->phone_verified_at),
            'verified_at' => $user->phone_verified_at
        ]);
    }
}
