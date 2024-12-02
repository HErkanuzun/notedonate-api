<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoteController extends Controller
{
    public function __construct()
    {
        // Remove the middleware from constructor since we'll handle it in routes
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $notes = Note::where('user_id', Auth::id())->paginate(25);
        $notesResource = NoteResource::collection($notes);
        
        return response()->json([
            'status' => true,
            'message' => 'Notes retrieved successfully',
            'notes' => $notesResource
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
        $note = Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
            'storage_link' => $request->storage_link,
            'viewer' => 0,
            'like' => 0
        ]);

        return new NoteResource($note);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        
        // Increment viewer count
        $note->increment('viewer');
        
        return new NoteResource($note);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request, $id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        
        $note->update($request->validated());
        
        return new NoteResource($note);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $note = Note::where('user_id', Auth::id())->findOrFail($id);
        $note->delete();
        
        return response()->json(['message' => 'Note deleted successfully']);
    }

    /**
     * Like a note
     */
    public function like($id)
    {
        $note = Note::findOrFail($id);
        $note->increment('like');
        
        return new NoteResource($note);
    }

    /**
     * Filtrelenmiş notları getir
     */
    public function filter(Request $request)
    {
        $query = Note::query();

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

        // Sayfalama
        $perPage = $request->input('per_page', 15);
        $notes = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $notes
        ]);
    }

    /**
     * Get all public notes
     */
    public function getAllNotes()
    {
        try {
            $notes = Note::with(['user'])
                ->latest()
                ->paginate(25);

            return response()->json([
                'status' => true,
                'message' => 'All notes retrieved successfully',
                'data' => $notes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving notes',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a public note
     */
    public function getPublicNote($id)
    {
        try {
            $note = Note::with(['user'])
                ->findOrFail($id);

            return response()->json([
                'status' => true,
                'message' => 'Note retrieved successfully',
                'data' => $note
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Error retrieving note',
                'error' => $e->getMessage()
            ], 404);
        }
    }
}
