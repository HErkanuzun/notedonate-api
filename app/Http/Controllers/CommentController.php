<?php

namespace App\Http\Controllers;

use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Exam;
use App\Models\Note;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Store a new comment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'commentable_type' => 'required|in:exam,note',
            'commentable_id' => 'required|integer'
        ]);

        // Model sınıfını belirle
        $modelClass = $validated['commentable_type'] === 'exam' ? Exam::class : Note::class;
        
        // İlgili modeli bul
        $model = $modelClass::findOrFail($validated['commentable_id']);

        // Yorumu oluştur
        $comment = $model->comments()->create([
            'content' => $validated['content'],
            'user_id' => auth()->id()
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment created successfully',
            'data' => $comment->load('user')
        ], 201);
    }

    /**
     * Get comments for a specific model.
     */
    public function getComments(Request $request, $type, $id)
    {
        $modelClass = $type === 'exam' ? Exam::class : Note::class;
        $model = $modelClass::findOrFail($id);

        $comments = $model->comments()->with('user')->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $comments
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = Comment::with(['user', 'article'])->get();
        return CommentResource::collection($comments);
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        return new CommentResource($comment->load(['user', 'article']));
    }

    /**
     * Update the specified comment.
     */
    public function update(Request $request, Comment $comment)
    {
        $this->authorize('update', $comment);

        $validated = $request->validate([
            'content' => 'required|string'
        ]);

        $comment->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Comment updated successfully',
            'data' => $comment->load('user')
        ]);
    }

    /**
     * Remove the specified comment.
     */
    public function destroy(Comment $comment)
    {
        $this->authorize('delete', $comment);

        $comment->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Comment deleted successfully'
        ]);
    }
}
