<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::with('questions')->get();
        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }

    /**
     * Display a listing of public exams.
     */
    public function publicIndex(Request $request)
    {
        $query = Exam::query()->where('status', 'scheduled');

        // Apply search filter
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                  ->orWhere('subject', 'like', "%{$searchTerm}%")
                  ->orWhere('university', 'like', "%{$searchTerm}%")
                  ->orWhere('department', 'like', "%{$searchTerm}%");
            });
        }

        // Apply individual filters
        if ($request->has('university')) {
            $query->where('university', $request->input('university'));
        }

        if ($request->has('department')) {
            $query->where('department', $request->input('department'));
        }

        if ($request->has('year')) {
            $query->where('year', $request->input('year'));
        }

        if ($request->has('semester')) {
            $query->where('semester', $request->input('semester'));
        }

        // Get unique values for filter options
        $universities = Exam::distinct()->pluck('university')->filter()->values();
        $departments = Exam::distinct()->pluck('department')->filter()->values();
        $years = Exam::distinct()->pluck('year')->filter()->values();
        $semesters = Exam::distinct()->pluck('semester')->filter()->values();

        // Paginate results
        $exams = $query->latest()->paginate(12);

        return response()->json([
            'data' => [
                'exams' => $exams->items(),
                'meta' => [
                    'current_page' => $exams->currentPage(),
                    'last_page' => $exams->lastPage(),
                    'per_page' => $exams->perPage(),
                    'total' => $exams->total(),
                ],
                'filters' => [
                    'universities' => $universities,
                    'departments' => $departments,
                    'years' => $years,
                    'semesters' => $semesters,
                ]
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'required|integer|min:0',
            'duration' => 'required|integer|min:1',
            'status' => 'required|in:active,completed,scheduled',
            'questions' => 'required|array|min:1',
            'questions.*.question' => 'required|string',
            'questions.*.question_type' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_option' => 'nullable|integer'
        ]);

        $exam = Exam::create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'total_marks' => $validated['total_marks'],
            'duration' => $validated['duration'],
            'status' => $validated['status'],
            'created_by' => auth()->id()
        ]);

        foreach ($validated['questions'] as $questionData) {
            $exam->questions()->create($questionData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Exam created successfully',
            'data' => $exam->load('questions')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        return response()->json([
            'status' => 'success',
            'data' => $exam->load('questions')
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'total_marks' => 'sometimes|integer|min:0',
            'duration' => 'sometimes|integer|min:1',
            'status' => 'sometimes|in:active,completed,scheduled',
            'questions' => 'sometimes|array|min:1',
            'questions.*.id' => 'sometimes|exists:exam_questions,id',
            'questions.*.question' => 'required|string',
            'questions.*.question_type' => 'required|string',
            'questions.*.options' => 'nullable|array',
            'questions.*.correct_option' => 'nullable|integer'
        ]);

        $exam->update($validated);

        if (isset($validated['questions'])) {
            // Delete existing questions not in the update
            $questionIds = array_column($validated['questions'], 'id');
            $exam->questions()->whereNotIn('id', $questionIds)->delete();

            // Update or create questions
            foreach ($validated['questions'] as $questionData) {
                if (isset($questionData['id'])) {
                    $exam->questions()->where('id', $questionData['id'])->update($questionData);
                } else {
                    $exam->questions()->create($questionData);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Exam updated successfully',
            'data' => $exam->load('questions')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam->questions()->delete();
        $exam->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Exam deleted successfully'
        ]);
    }
}
