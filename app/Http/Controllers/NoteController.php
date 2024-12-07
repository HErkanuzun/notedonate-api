<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Http\Requests\StoreNoteRequest;
use App\Http\Requests\UpdateNoteRequest;
use App\Http\Resources\NoteResource;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['publicIndex', 'publicShow']);
    }

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
     * Display a listing of public notes.
     */
    public function publicIndex(Request $request)
    {
        $query = Note::query();

        // İndirme sayısına göre sırala
        $query->orderBy('downloads', 'desc');

        // Limit
        $notes = $query->take(6)->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'notes' => $notes
            ]
        ]);
    }

    /**
     * Display the specified note for public view
     */
    public function publicShow(string $id)
    {
        $note = Note::with(['user'])->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => new NoteResource($note)
        ]);
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
            $storenote->status = $request->status;
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
        if (isset($request->status)) {
            $storenote->status = $request->status;
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
}
