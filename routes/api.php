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

// Public routes that don't require authentication
Route::prefix('v1')->group(function () {
    // Note routes
    Route::get('/note',[NoteController::class,'index']);
    Route::get('/note/show/{id}',[NoteController::class,'show']);
    Route::post('/note/store',[NoteController::class,'store']);
    Route::post('/note/update/{id}',[NoteController::class,'update']);
    Route::post('/note/destroy/{id}',[NoteController::class,'destroy']);

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
    Route::get('/exam/questions',[ExamQuestionController::class,'index']);
    Route::get('/exam/questions/show/{id}',[ExamQuestionController::class,'show']);
    Route::post('/exam/questions/store',[ExamQuestionController::class,'store']);
    Route::post('/exam/questions/update/{id}',[ExamQuestionController::class,'update']);
    Route::post('/exam/questions/destroy/{id}',[ExamQuestionController::class,'destroy']);

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
        Route::post('/', [SettingController::class, 'store']);
        Route::get('/{setting}', [SettingController::class, 'show']);
        Route::put('/{setting}', [SettingController::class, 'update']);
        Route::delete('/{setting}', [SettingController::class, 'destroy']);
        Route::get('/group/{group}', [SettingController::class, 'getGroup']);
        Route::post('/bulk-update', [SettingController::class, 'bulkUpdate']);
    });

    // Üniversite ve Bölüm rotaları
    Route::apiResource('universities', UniversityController::class);
    Route::apiResource('departments', DepartmentController::class);

    // Filtreleme rotaları
    Route::get('notes/filter', [NoteController::class, 'filter']);
    Route::get('exams/filter', [ExamController::class, 'filter']);
});

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});