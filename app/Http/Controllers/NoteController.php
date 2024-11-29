<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    $cachednote = cache()->remember('notecache',now()->addMinutes(50),function(){
        return Note::paginate(25);
    });
    return NoteResource::collection($cachednote);

    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreNoteRequest $request)
    {
     
            $storenote = new Note();
            $storenote->title = $request->title;
            $storenote->content = $request->content;
            $storenote->storage_link = $request->storage_link;
            $storenote->viewer = $request->viewer;
            $storenote->like = $request->like;
            $storenote->save();
            return response()->json(
                [
                    'message'=> "created"
                ],201 
            );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {

        if(isset(($id)))
        $cachenote = cache()->remember("note$id",now()->addMinutes(50),function() use ($id) {
            return Note::find($id);
        });

        return new NoteResource($cachenote);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateNoteRequest $request)
    {
             
        $storenote = note::find($request->id);
        if (isset($request->title)) {
            $storenote->title = $request->title;
        }
        
        if (isset($request->content)) {
            $storenote->content = $request->content;
        }
        
        if (isset($request->storage_link)) {
            $storenote->storage_link = $request->storage_link;
        }
        
        if (isset($request->viewer)) {
            $storenote->viewer = $request->viewer;
        }
        
        if (isset($request->like)) {
            $storenote->like = $request->like;
        }
        $storenote->save();
        return response()->json(
            [
                'message'=> "created"
            ],201 
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $note = Note::find($id);

        if(!$note){
            return response()->json(['message' => 'Note is not found!'], 404);
        }
        $note->delete();

        return response()->json(['message'=>'Note is deleted!'], 200);

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
}
