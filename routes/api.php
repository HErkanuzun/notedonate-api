<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamQuestionController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PhoneVerificationController;

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

Route::prefix('v1')->group(function () {
    // Public routes that don't require authentication
    Route::post('/register',[AuthController::class,'register']);
    Route::post('/login',[AuthController::class,'login']);

    Route::get('/email/verify/{id}/{hash}', [AuthController::class, 'verifyEmail'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [AuthController::class, 'resendVerificationEmail'])
        ->middleware(['throttle:6,1'])
        ->name('verification.send');

    // Şifre sıfırlama route'ları
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword'])
        ->middleware(['throttle:6,1'])
        ->name('password.email');

    Route::get('/reset-password', [AuthController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [AuthController::class, 'resetPassword'])
        ->name('password.update');

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout',[AuthController::class,'logout']);
        Route::get('/user', function (Request $request) {
            return $request->user();
        });

        // Note routes
        Route::apiResource('notes', NoteController::class);
        Route::post('notes/{id}/like', [NoteController::class, 'like']);

        // Exam routes
        Route::get('/exam',[ExamController::class,'index']);
        Route::get('/exam/show/{id}',[ExamController::class,'show']);
        Route::post('/exam/store',[ExamController::class,'store']);
        Route::post('/exam/update/{id}',[ExamController::class,'update']);
        Route::post('/exam/destroy/{id}',[ExamController::class,'destroy']);

        // Comment routes
        Route::get('/comments/{type}/{id}', [CommentController::class, 'getComments']);
        Route::post('/comments', [CommentController::class, 'store']);
        Route::put('/comments/{comment}', [CommentController::class, 'update']);
        Route::delete('/comments/{comment}', [CommentController::class, 'destroy']);

        // Event routes
        Route::get('/event',[EventController::class,'index']);
        Route::get('/event/show/{id}',[EventController::class,'show']);
        Route::post('/event/store',[EventController::class,'store']);
        Route::post('/event/update/{id}',[EventController::class,'update']);
        Route::post('/event/destroy/{id}',[EventController::class,'destroy']);

        // Exam Question routes
        Route::get('/exam-questions', [ExamQuestionController::class, 'index']);
        Route::get('/exam-questions/{examQuestion}', [ExamQuestionController::class, 'show']);
        Route::post('/exam-questions/store', [ExamQuestionController::class, 'store']);
        Route::put('/exam-questions/{examQuestion}', [ExamQuestionController::class, 'update']);
        Route::delete('/exam-questions/{examQuestion}', [ExamQuestionController::class, 'destroy']);

        // Article Routes
        Route::prefix('articles')->group(function () {
            Route::get('/', [ArticleController::class, 'index']);
            Route::get('/{article}', [ArticleController::class, 'show']);
            Route::get('/category/{categorySlug}', [ArticleController::class, 'byCategory']);
            Route::post('/', [ArticleController::class, 'store']);
            Route::put('/{article}', [ArticleController::class, 'update']);
            Route::delete('/{article}', [ArticleController::class, 'destroy']);
        });

        // Article Category Routes
        Route::prefix('categories')->group(function () {
            Route::get('/', [ArticleCategoryController::class, 'index']);
            Route::get('/{category}', [ArticleCategoryController::class, 'show']);
            Route::post('/', [ArticleCategoryController::class, 'store']);
            Route::put('/{category}', [ArticleCategoryController::class, 'update']);
            Route::delete('/{category}', [ArticleCategoryController::class, 'destroy']);
        });

        // Settings Routes
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']);
            Route::post('/', [SettingController::class, 'update']);
            
            // Theme settings
            Route::get('/theme', [SettingController::class, 'getThemeSettings']);
            Route::post('/theme', [SettingController::class, 'updateThemeSettings']);
            
            // UI settings
            Route::get('/ui', [SettingController::class, 'getUiSettings']);
            Route::post('/ui', [SettingController::class, 'updateUiSettings']);
            
            // Social media settings
            Route::get('/social', [SettingController::class, 'getSocialSettings']);
            Route::post('/social', [SettingController::class, 'updateSocialSettings']);
            
            // SEO settings
            Route::get('/seo', [SettingController::class, 'getSeoSettings']);
            Route::post('/seo', [SettingController::class, 'updateSeoSettings']);
        });

        // Üniversite ve Bölüm rotaları
        Route::prefix('universities')->group(function () {
            Route::get('/', [UniversityController::class, 'index']);
            Route::post('/', [UniversityController::class, 'store']);
            Route::get('/{university}', [UniversityController::class, 'show']);
            Route::put('/{university}', [UniversityController::class, 'update']);
            Route::delete('/{university}', [UniversityController::class, 'destroy']);
            
            // Üniversiteye ait bölümler
            Route::get('/{university}/departments', [DepartmentController::class, 'getByUniversity']);
        });

        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index']);
            Route::post('/', [DepartmentController::class, 'store']);
            Route::get('/{department}', [DepartmentController::class, 'show']);
            Route::put('/{department}', [DepartmentController::class, 'update']);
            Route::delete('/{department}', [DepartmentController::class, 'destroy']);
        });

        // Role yönetimi rotaları
        Route::prefix('roles')->middleware(['auth:sanctum'])->group(function () {
            // Herkes görüntüleyebilir
            Route::get('/', [RoleController::class, 'index']);
            Route::get('/{role}', [RoleController::class, 'show']);
            Route::get('/user/{userId}', [RoleController::class, 'getUserRoles']);

            // Sadece super_admin yapabilir
            Route::middleware(['role:super_admin'])->group(function () {
                Route::post('/', [RoleController::class, 'store']);
                Route::put('/{role}', [RoleController::class, 'update']);
                Route::delete('/{role}', [RoleController::class, 'destroy']);
                Route::post('/assign', [RoleController::class, 'assignRole']);
                Route::post('/remove', [RoleController::class, 'removeRole']);
            });
        });

        // Profile Routes
        Route::middleware('auth:api')->group(function () {
            // Phone verification routes
            Route::post('/profile/phone/verify/send', [PhoneVerificationController::class, 'sendCode']);
            Route::post('/profile/phone/verify/confirm', [PhoneVerificationController::class, 'verifyCode']);
            Route::post('/profile/phone/verify/resend', [PhoneVerificationController::class, 'resendCode']);
            
            // Get verification status
            Route::get('/profile/phone/status', [PhoneVerificationController::class, 'getStatus']);
        });

        // Phone Verification Routes
        Route::post('/verify/phone/send', [PhoneVerificationController::class, 'sendCode']);
        Route::post('/verify/phone/verify', [PhoneVerificationController::class, 'verifyCode'])->middleware('auth:api');
        Route::post('/verify/phone/resend', [PhoneVerificationController::class, 'resendCode']);

        // Filtreleme rotaları
        Route::get('notes/filter', [NoteController::class, 'filter']);
        Route::get('exams/filter', [ExamController::class, 'filter']);
    });
});