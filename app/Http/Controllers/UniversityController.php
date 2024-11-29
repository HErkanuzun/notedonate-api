<?php

namespace App\Http\Controllers;

use App\Http\Resources\UniversityResource;
use App\Models\University;
use Illuminate\Http\Request;

class UniversityController extends Controller
{
    /**
     * Display a listing of universities.
     */
    public function index()
    {
        $universities = University::with('departments')->get();
        return UniversityResource::collection($universities);
    }

    /**
     * Store a newly created university.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'logo' => 'nullable|string'
        ]);

        $university = University::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Üniversite başarıyla eklendi',
            'data' => $university
        ], 201);
    }

    /**
     * Display the specified university.
     */
    public function show(University $university)
    {
        return new UniversityResource($university->load('departments'));
    }

    /**
     * Update the specified university.
     */
    public function update(Request $request, University $university)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'website' => 'nullable|url',
            'description' => 'nullable|string',
            'logo' => 'nullable|string'
        ]);

        $university->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Üniversite başarıyla güncellendi',
            'data' => $university
        ]);
    }

    /**
     * Remove the specified university.
     */
    public function destroy(University $university)
    {
        $university->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Üniversite başarıyla silindi'
        ]);
    }
}
