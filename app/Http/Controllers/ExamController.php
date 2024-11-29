<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use Illuminate\Http\Request;
use App\Http\Resources\ExamResource;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::with('questions')->get();
        return ExamResource::collection($exams);
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
            'created_by' => 'required|exists:users,id',
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
            'created_by' => $validated['created_by']
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
        return new ExamResource($exam->load('questions'));
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

    /**
     * Filtrelenmiş sınavları getir
     */
    public function filter(Request $request)
    {
        $query = Exam::query();

        // Üniversite filtresi
        if ($request->has('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        // Bölüm filtresi
        if ($request->has('department_id')) {
            $query->where('department_id', $request->department_id);
        }

        // Yıl filtresi
        if ($request->has('year')) {
            $query->where('year', $request->year);
        }

        // Dönem filtresi
        if ($request->has('semester')) {
            $query->where('semester', $request->semester);
        }

        // Tarih filtresi
        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sıralama
        $sortField = $request->input('sort_by', 'created_at');
        $sortDirection = $request->input('sort_direction', 'desc');
        $query->orderBy($sortField, $sortDirection);

        // İlişkili verileri yükle
        $query->with(['questions', 'creator']);

        // Sayfalama
        $perPage = $request->input('per_page', 15);
        $exams = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $exams
        ]);
    }
}
