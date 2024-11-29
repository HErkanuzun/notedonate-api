<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\ArticleCategoryController;
use App\Http\Controllers\SettingController;

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

// Article Routes
Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index']);
    Route::get('/{article}', [ArticleController::class, 'show']);
    Route::get('/category/{categorySlug}', [ArticleController::class, 'byCategory']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ArticleController::class, 'store']);
        Route::put('/{article}', [ArticleController::class, 'update']);
        Route::delete('/{article}', [ArticleController::class, 'destroy']);
    });
});

// Article Category Routes
Route::prefix('categories')->group(function () {
    Route::get('/', [ArticleCategoryController::class, 'index']);
    Route::get('/{category}', [ArticleCategoryController::class, 'show']);
    
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/', [ArticleCategoryController::class, 'store']);
        Route::put('/{category}', [ArticleCategoryController::class, 'update']);
        Route::delete('/{category}', [ArticleCategoryController::class, 'destroy']);
    });
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