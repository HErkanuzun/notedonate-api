<?php

namespace App\Http\Controllers;

use App\Models\ExamQuestion;
use App\Http\Requests\StoreExamQuestionRequest;
use App\Http\Requests\UpdateExamQuestionRequest;
use Illuminate\Http\Request;

class ExamQuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $questions = ExamQuestion::with(['exam'])->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $questions
        ]);
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
    public function store(StoreExamQuestionRequest $request)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamQuestion $examQuestion)
    {
        //
    }
}
