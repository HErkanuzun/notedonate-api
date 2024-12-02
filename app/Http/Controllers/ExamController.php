<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;
use App\Http\Resources\ExamResource;
use Carbon\Carbon;

class ExamController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.ownership')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = Exam::query()
                ->where('created_by', auth()->id())
                ->with('creator');

            // Filtreleme
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('subject')) {
                $query->where('subject', $request->subject);
            }

            if ($request->has('exam_date')) {
                $query->whereDate('exam_date', $request->exam_date);
            }

            // Sıralama
            $query->orderBy('exam_date', 'asc');

            $exams = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'status' => 'success',
                'data' => ExamResource::collection($exams),
                'meta' => [
                    'total' => $exams->total(),
                    'current_page' => $exams->currentPage(),
                    'last_page' => $exams->lastPage(),
                    'per_page' => $exams->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'subject' => 'required|string|max:255',
                'description' => 'nullable|string',
                'exam_date' => 'required|date|after_or_equal:today',
                'duration' => 'required|integer|min:1',
                'total_marks' => 'required|integer|min:0',
                'status' => 'required|in:upcoming,completed,cancelled'
            ]);

            $validated['created_by'] = auth()->id();
            
            $exam = Exam::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Exam created successfully',
                'data' => new ExamResource($exam->load('creator'))
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Exam $exam)
    {
        return new ExamResource($exam->load('creator'));
    }

    public function update(Request $request, Exam $exam)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'subject' => 'sometimes|string|max:255',
                'description' => 'nullable|string',
                'exam_date' => 'sometimes|date|after_or_equal:today',
                'duration' => 'sometimes|integer|min:1',
                'total_marks' => 'sometimes|integer|min:0',
                'status' => 'sometimes|in:upcoming,completed,cancelled'
            ]);

            $exam->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Exam updated successfully',
                'data' => new ExamResource($exam->fresh()->load('creator'))
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Exam $exam)
    {
        try {
            $exam->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Exam deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete exam',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function upcoming()
    {
        try {
            $exams = Exam::where('status', 'upcoming')
                ->where('created_by', auth()->id())
                ->where('exam_date', '>', Carbon::now())
                ->orderBy('exam_date', 'asc')
                ->with('creator')
                ->paginate(10);

            return response()->json([
                'status' => 'success',
                'data' => ExamResource::collection($exams),
                'meta' => [
                    'total' => $exams->total(),
                    'current_page' => $exams->currentPage(),
                    'last_page' => $exams->lastPage(),
                    'per_page' => $exams->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch upcoming exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all public exams
     */
    public function getAllExams()
    {
        try {
            $exams = Exam::with(['user'])
                ->latest()
                ->paginate(25);

            return response()->json([
                'status' => true,
                'message' => 'All exams retrieved successfully',
                'data' => $exams
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving exams',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a public exam
     */
    public function getPublicExam($id)
    {
        try {
            $exam = Exam::with(['user', 'questions'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Exam retrieved successfully',
                'data' => $exam
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving exam',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
