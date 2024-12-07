<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\ExamQuestionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// User Profile Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::post('/user/profile', [UserController::class, 'updateProfile']);
    Route::post('/user/verify-phone', [UserController::class, 'verifyPhone']);
    Route::post('/user/resend-phone-verification', [UserController::class, 'resendPhoneVerification']);
});

Route::prefix('v1')->group(function () {
    // API HEALT CHECH
    Route::get('/health', function () {
        return response()->json(['status' => 'OK'], 200);
    });
    // Public Routes
    Route::prefix('public')->group(function () {
        // Public Articles
        Route::get('articles', [ArticleController::class, 'publicIndex']);
        Route::get('articles/{id}', [ArticleController::class, 'publicShow']);
    
        // Public Notes
        Route::get('/notes', [NoteController::class, 'publicIndex']);
        Route::get('/notes/{id}', [NoteController::class, 'publicShow']);
    
        // Public Events
        Route::get('/events', [EventController::class, 'publicIndex']);
        Route::get('/events/{id}', [EventController::class, 'publicShow']);
        
        // Public Exams
        Route::get('/exams', [ExamController::class, 'publicIndex']);
        Route::get('/exams/{id}', [ExamController::class, 'publicShow']);
    });

    // Auth Routes
    Route::prefix('auth')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/verify-token', [AuthController::class, 'verifyToken']);
        Route::post('/reset-password', [AuthController::class, 'resetPassword']);
        Route::middleware('auth:sanctum')->group(function () {
            Route::get('/user', [AuthController::class, 'userData']);
            Route::post('/logout', [AuthController::class, 'logout']);
        });
    });

    // Article Routes
    Route::prefix('articles')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ArticleController::class, 'index']);
        Route::get('/{article}', [ArticleController::class, 'show']);
        Route::get('/category/{categorySlug}', [ArticleController::class, 'byCategory']);
        Route::post('/', [ArticleController::class, 'store']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);
    });

    // Article Category Routes
    Route::prefix('categories')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ArticleCategoryController::class, 'index']);
        Route::get('/{category}', [ArticleCategoryController::class, 'show']);
        Route::post('/', [ArticleCategoryController::class, 'store']);
        Route::put('/{category}', [ArticleCategoryController::class, 'update']);
        Route::delete('/{category}', [ArticleCategoryController::class, 'destroy']);
    });

    // Settings Routes
    Route::prefix('settings')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [SettingController::class, 'index']);
        Route::post('/', [SettingController::class, 'store']);
        Route::get('/{setting}', [SettingController::class, 'show']);
        Route::put('/{setting}', [SettingController::class, 'update']);
        Route::delete('/{setting}', [SettingController::class, 'destroy']);
        Route::get('/group/{group}', [SettingController::class, 'getGroup']);
        Route::post('/bulk-update', [SettingController::class, 'bulkUpdate']);
    });

    // Note Routes
    Route::prefix('notes')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [NoteController::class, 'index']);
        Route::get('/{note}', [NoteController::class, 'show']);
        Route::post('/', [NoteController::class, 'store']);
        Route::put('/{note}', [NoteController::class, 'update']);
        Route::delete('/{note}', [NoteController::class, 'destroy']);
    });

    // Event Routes
    Route::prefix('events')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [EventController::class, 'index']);
        Route::get('/{event}', [EventController::class, 'show']);
        Route::post('/', [EventController::class, 'store']);
        Route::put('/{event}', [EventController::class, 'update']);
        Route::delete('/{event}', [EventController::class, 'destroy']);
    });

    // Exam Routes
    Route::prefix('exams')->middleware('auth:sanctum')->group(function () {
        Route::get('/', [ExamController::class, 'index']);
        Route::get('/{exam}', [ExamController::class, 'show']);
        Route::post('/', [ExamController::class, 'store']);
        Route::put('/{exam}', [ExamController::class, 'update']);
        Route::delete('/{exam}', [ExamController::class, 'destroy']);

        // Nested routes for exam questions
        Route::prefix('/{exam}/questions')->group(function () {
            Route::get('/', [ExamQuestionController::class, 'index']);
            Route::get('/{question}', [ExamQuestionController::class, 'show']);
            Route::post('/', [ExamQuestionController::class, 'store']);
            Route::put('/{question}', [ExamQuestionController::class, 'update']);
            Route::delete('/{question}', [ExamQuestionController::class, 'destroy']);
        });
    });

    // User routes
    Route::prefix('users')->group(function () {
        Route::get('{id}/profile', [UserController::class, 'getProfile']);
        Route::put('{id}/profile', [UserController::class, 'updateProfile'])->middleware('auth:sanctum');
        Route::get('{id}/stats', [UserController::class, 'getStats']);
        Route::post('{id}/follow', [UserController::class, 'follow'])->middleware('auth:sanctum');
        Route::delete('{id}/follow', [UserController::class, 'unfollow'])->middleware('auth:sanctum');
        Route::get('{id}/followers', [UserController::class, 'getFollowers']);
        Route::get('{id}/following', [UserController::class, 'getFollowing']);
    });

    // Email Verification Routes
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();
        return response()->json(['message' => 'Your email has been successfully verified.']);
    })->middleware(['auth:sanctum', 'signed'])->name('verification.verify');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Verification link sent.']);
    })->middleware(['auth:sanctum', 'throttle:6,1'])->name('verification.send');
});