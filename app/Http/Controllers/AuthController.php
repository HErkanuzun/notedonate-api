<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifEmail;
use App\Mail\ResetPassword;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request) {
        try {
            $validator = validator($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:6|confirmed',
                'password_confirmation' => 'required'
            ], [
                'name.required' => 'İsim alanı zorunludur.',
                'name.string' => 'İsim metin formatında olmalıdır.',
                'name.max' => 'İsim en fazla 255 karakter olabilir.',
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'password.required' => 'Şifre alanı zorunludur.',
                'password.min' => 'Şifre en az 6 karakter olmalıdır.',
                'password.confirmed' => 'Şifreler eşleşmiyor.',
                'password_confirmation.required' => 'Şifre tekrarı zorunludur.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
        
            // Check if the email or phone already exists
            if (User::where('email', $request->email)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email already exists.'
                ], 400);
            }

            if ($request->phone && User::where('phone', $request->phone)->exists()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number already exists.'
                ], 400);
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'profile_photo_path' => 'photo-default.jpg'
            ]);

            Log::info('User created successfully', ['user_id' => $user->id]);

            // Generate verification URL
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                now()->addMinutes(60),
                ['id' => $user->id, 'hash' => sha1($user->email)]
            );

            Log::info('Verification URL generated', ['url' => $verificationUrl]);

            // Send verification email
            try {
                Mail::to($request->email)->send(new VerifEmail($verificationUrl));
                Log::info('Verification email sent successfully', ['email' => $request->email]);
                
                $token = $user->createToken('auth_token')->plainTextToken;

                return response()->json([
                    "status" => true,
                    "message" => "Kayıt başarılı! Lütfen e-posta adresinizi doğrulayın.",
                    "user" => $user,
                    "token" => $token,
                    "debug_url" => $verificationUrl // Geliştirme aşamasında URL'i görmek için
                ], 201);
            } catch (\Exception $e) {
                Log::error('Failed to send verification email', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return response()->json([
                    "status" => false,
                    "message" => "Kullanıcı kaydedildi fakat doğrulama e-postası gönderilemedi. Hata: " . $e->getMessage(),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Registration failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Eğer unique constraint hatası ise
            if (strpos($e->getMessage(), 'users_email_unique') !== false) {
                return response()->json([
                    "status" => false,
                    "message" => "Bu e-posta adresi zaten kayıtlı.",
                ], 422);
            }
            
            return response()->json([
                "status" => false,
                "message" => "Kayıt işlemi başarısız: " . $e->getMessage(),
            ], 400);
        }
    }

    public function login(Request $request) {
        try {
            $validator = validator($request->all(), [
                'email' => 'required|email',
                'password' => 'required',
            ], [
                'email.required' => 'E-posta alanı zorunludur.',
                'email.email' => 'Geçerli bir e-posta adresi giriniz.',
                'password.required' => 'Şifre alanı zorunludur.'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validation error',
                    'errors' => $validator->errors()
                ], 422);
            }
        
            if (!auth()->attempt($request->only('email', 'password'))) {
                return response()->json([
                    "status" => false,
                    "message" => "E-posta veya şifre hatalı."
                ], 401);
            }

            $user = auth()->user();
            
            if (!$user->hasVerifiedEmail()) {
                return response()->json([
                    "status" => false,
                    "message" => "Lütfen önce e-posta adresinizi doğrulayın."
                ], 403);
            }
            
            $token = $user->createToken('auth_token')->plainTextToken;
            
            return response()->json([
                "status" => true,
                "message" => "Giriş başarılı!",
                "user" => $user,
                "token" => $token
            ]);
        } catch (\Exception $e) {
            Log::error('Login failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                "status" => false,
                "message" => "Giriş başarısız: " . $e->getMessage(),
            ], 400);
        }
    }

    public function verifyEmail(Request $request, $id) {
        try {
            $user = User::findOrFail($id);
            
            if (!$request->hasValidSignature()) {
                return response()->json([
                    "status" => false,
                    "message" => "Invalid verification link or link has expired."
                ], 400);
            }

            if (!$user->hasVerifiedEmail()) {
                $user->markEmailAsVerified();
                Log::info('Email verified successfully', ['user_id' => $user->id]);
            }

            return response()->json([
                "status" => true,
                "message" => "Email verified successfully"
            ]);
        } catch (\Exception $e) {
            Log::error('Email verification failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                "status" => false,
                "message" => "Email verification failed: " . $e->getMessage(),
            ], 500);
        }
    }

    public function logout(Request $request) {
        try {
            auth()->user()->tokens()->delete();
            
            return response()->json([
                "status" => true,
                "message" => "Çıkış başarılı!"
            ]);
        } catch (\Exception $e) {
            Log::error('Logout failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                "status" => false,
                "message" => "Çıkış başarısız: " . $e->getMessage(),
            ], 400);
        }
    }

    public function forgotPassword(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users',
            ]);

            $token = Str::random(64);

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => $token,
                'created_at' => now()
            ]);

            $resetUrl = url('/api/v1/reset-password') . '?token=' . $token . '&email=' . $request->email;

            Mail::to($request->email)->send(new ResetPassword($resetUrl));

            return response()->json([
                'status' => true,
                'message' => 'Şifre sıfırlama bağlantısı email adresinize gönderildi.',
                'debug_url' => $resetUrl // Geliştirme aşamasında URL'i görmek için
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset request failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Şifre sıfırlama işlemi başarısız: ' . $e->getMessage()
            ], 500);
        }
    }

    public function showResetForm(Request $request)
    {
        $token = $request->query('token');
        $email = $request->query('email');

        if (!$token || !$email) {
            return response()->json([
                'status' => false,
                'message' => 'Geçersiz şifre sıfırlama linki.'
            ], 400);
        }

        return response()->json([
            'status' => true,
            'message' => 'Şifre sıfırlama formu.',
            'data' => [
                'token' => $token,
                'email' => $email
            ]
        ]);
    }

    public function resetPassword(Request $request)
    {
        try {
            $request->validate([
                'token' => 'required',
                'email' => 'required|email',
                'password' => 'required|min:6|confirmed',
            ]);

            $resetRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('token', $request->token)
                ->first();

            if (!$resetRecord) {
                return response()->json([
                    'status' => false,
                    'message' => 'Geçersiz şifre sıfırlama tokeni.'
                ], 400);
            }

            $user = User::where('email', $request->email)->first();
            
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Kullanıcı bulunamadı.'
                ], 404);
            }

            $user->password = Hash::make($request->password);
            $user->save();

            DB::table('password_reset_tokens')->where('email', $request->email)->delete();

            return response()->json([
                'status' => true,
                'message' => 'Şifreniz başarıyla güncellendi.'
            ]);

        } catch (\Exception $e) {
            Log::error('Password reset failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'status' => false,
                'message' => 'Şifre sıfırlama başarısız: ' . $e->getMessage()
            ], 500);
        }
    }
}
