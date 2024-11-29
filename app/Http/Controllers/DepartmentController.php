<?php

namespace App\Http\Controllers;

use App\Http\Resources\DepartmentResource;
use App\Models\Department;
use App\Models\University;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of departments.
     */
    public function index(Request $request)
    {
        $query = Department::with('university');
        
        // Üniversiteye göre filtrele
        if ($request->has('university_id')) {
            $query->where('university_id', $request->university_id);
        }

        $departments = $query->get();
        
        return DepartmentResource::collection($departments);
    }

    /**
     * Store a newly created department.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'university_id' => 'required|exists:universities,id',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'website' => 'nullable|url'
        ]);

        $department = Department::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Bölüm başarıyla eklendi',
            'data' => $department
        ], 201);
    }

    /**
     * Display the specified department.
     */
    public function show(Department $department)
    {
        return new DepartmentResource($department->load('university'));
    }

    /**
     * Update the specified department.
     */
    public function update(Request $request, Department $department)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'university_id' => 'sometimes|required|exists:universities,id',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'head_of_department' => 'nullable|string|max:255',
            'website' => 'nullable|url'
        ]);

        $department->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Bölüm başarıyla güncellendi',
            'data' => $department
        ]);
    }

    /**
     * Remove the specified department.
     */
    public function destroy(Department $department)
    {
        $department->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Bölüm başarıyla silindi'
        ]);
    }

    /**
     * Get departments by university.
     */
    public function getByUniversity(University $university)
    {
        $departments = $university->departments()->get();

        return response()->json([
            'status' => 'success',
            'data' => $departments
        ]);
    }
}
