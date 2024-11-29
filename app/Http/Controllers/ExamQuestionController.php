<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use App\Http\Requests\StoreExamQuestionRequest;
use App\Http\Requests\UpdateExamQuestionRequest;
use App\Http\Resources\ExamQuestionResource;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = ExamQuestion::with(['exam'])->get();
        
        return ExamQuestionResource::collection($questions);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $question = new ExamQuestion();
        $question->exam_id = $request->input('exam_id');
        $question->question = $request->input('question');
        $question->question_type = $request->input('question_type');
        $question->options = $request->input('options');
        $question->correct_answer = $request->input('correct_option');
        $question->marks = $request->input('marks');
        $question->save();
        return response()->json([
            'status' => 'success',
            'message' => 'Question created successfully',
            'data' => $question
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $question = ExamQuestion::with(['exam'])->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $question
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ExamQuestion $examQuestion)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateExamQuestionRequest $request, ExamQuestion $examQuestion)
    {
        $examQuestion->update($request->validated());
        
        return response()->json([
            'status' => 'success',
            'message' => 'Question updated successfully',
            'data' => $examQuestion
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamQuestion $examQuestion)
    {
        $examQuestion->delete();
        
        return response()->json([
            'status' => 'success',
            'message' => 'Question deleted successfully'
        ]);
    }
}
