<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Http\Resources\ArticleResource;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
        $this->middleware('check.ownership')->only(['show', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        try {
            $query = Article::query()
                ->where('created_by', auth()->id())
                ->with('creator');

            // Filtreleme
            if ($request->has('status')) {
                $query->where('status', $request->status);
            }

            if ($request->has('category')) {
                $query->where('category', $request->category);
            }

            if ($request->has('tag')) {
                $query->whereJsonContains('tags', $request->tag);
            }

            // Sıralama
            $sortBy = $request->get('sort_by', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortBy, $sortOrder);

            $articles = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'status' => 'success',
                'data' => ArticleResource::collection($articles),
                'meta' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'title' => 'required|string|max:255',
                'content' => 'required|string',
                'category' => 'required|string|max:50',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:30',
                'status' => 'required|in:draft,published,archived',
                'publish_date' => 'nullable|date|after_or_equal:today'
            ]);

            $validated['created_by'] = auth()->id();
            
            $article = Article::create($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Article created successfully',
                'data' => new ArticleResource($article->load('creator'))
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
                'message' => 'Failed to create article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show(Article $article)
    {
        return new ArticleResource($article->load('creator'));
    }

    public function update(Request $request, Article $article)
    {
        try {
            $validated = $request->validate([
                'title' => 'sometimes|string|max:255',
                'content' => 'sometimes|string',
                'category' => 'sometimes|string|max:50',
                'tags' => 'nullable|array',
                'tags.*' => 'string|max:30',
                'status' => 'sometimes|in:draft,published,archived',
                'publish_date' => 'nullable|date|after_or_equal:today'
            ]);

            $article->update($validated);

            return response()->json([
                'status' => 'success',
                'message' => 'Article updated successfully',
                'data' => new ArticleResource($article->fresh()->load('creator'))
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
                'message' => 'Failed to update article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Article $article)
    {
        try {
            $article->delete();

            return response()->json([
                'status' => 'success',
                'message' => 'Article deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete article',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function search(Request $request)
    {
        try {
            $query = Article::query()
                ->where('created_by', auth()->id())
                ->where(function($q) use ($request) {
                    $searchTerm = $request->get('q');
                    $q->where('title', 'like', "%{$searchTerm}%")
                      ->orWhere('content', 'like', "%{$searchTerm}%")
                      ->orWhere('category', 'like', "%{$searchTerm}%")
                      ->orWhereJsonContains('tags', $searchTerm);
                })
                ->with('creator');

            $articles = $query->paginate($request->get('per_page', 10));

            return response()->json([
                'status' => 'success',
                'data' => ArticleResource::collection($articles),
                'meta' => [
                    'total' => $articles->total(),
                    'current_page' => $articles->currentPage(),
                    'last_page' => $articles->lastPage(),
                    'per_page' => $articles->perPage()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to search articles',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
