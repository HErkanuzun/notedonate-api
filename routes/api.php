<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ExamQuestionController;
use App\Models\ExamQuestion;
use App\Models\Note;


Route::get('/', function() {
    return response()->json([
        'message' => 'Hello, Api'
    ], 200);
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/note',[NoteController::class,'index']);
Route::get('/note/show/{id}',[NoteController::class,'show']);
Route::post('/note/store',[NoteController::class,'store']);
Route::post('/note/update/{id}',[NoteController::class,'update']);
Route::post('/note/destroy/{id}',[NoteController::class,'destroy']);

Route::get('/exam',[ExamController::class,'index']);
Route::get('/exam/show/{id}',[ExamController::class,'show']);
Route::post('/exam/store',[ExamController::class,'store']);
Route::post('/exam/update/{id}',[ExamController::class,'update']);
Route::post('/exam/destroy/{id}',[ExamController::class,'destroy']);

Route::get('/event',[EventController::class,'index']);
Route::get('/event/show/{id}',[EventController::class,'show']);
Route::post('/event/store',[EventController::class,'store']);
Route::post('/event/update/{id}',[EventController::class,'update']);
Route::post('/event/destroy/{id}',[EventController::class,'destroy']);

Route::get('/exam/questions',[ExamQuestionController::class,'index']);
Route::get('/exam/questions/show/{id}',[ExamQuestionController::class,'show']);
Route::post('/exam/questions/store',[ExamQuestionController::class,'store']);
Route::post('/exam/questions/update/{id}',[ExamQuestionController::class,'update']);
Route::post('/exam/questions/destroy/{id}',[ExamQuestionController::class,'destroy']);


Route::get('/test',function(){


    $cached = cache()->remember('my_test_data',now()->addMinutes(5),function(){ 
        return Note::paginate(150);
    });


    return response()->json(
        [
            'status' => 200,
            'data' => $cached
        ]
        );

});     