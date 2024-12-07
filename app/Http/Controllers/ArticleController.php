<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\ArticleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['publicIndex', 'publicShow']);
    }

    /**
     * Display a listing of articles.
     */
    public function index(Request $request)
    {
        $query = Article::with(['author', 'categories', 'comments']);

        // Filtreleme
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('category')) {
            $query->whereHas('categories', function ($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        if ($request->has('author')) {
            $query->whereHas('author', function ($q) use ($request) {
                $q->where('id', $request->author);
            });
        }

        // Yayınlanmış makaleleri getir
        if ($request->boolean('published_only', false)) {
            $query->published();
        }

        // Sıralama
        $query->orderBy('created_at', 'desc');

        $articles = $query->paginate($request->input('per_page', 15));

        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }

    /**
     * Store a newly created article.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'featured_image' => 'nullable|image|max:2048', // 2MB max
            'excerpt' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'categories' => 'array',
            'categories.*' => 'exists:article_categories,id',
            'published_at' => 'nullable|date'
        ]);

        // Resim yükleme
        if ($request->hasFile('featured_image')) {
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        $article = Article::create([
            ...$validated,
            'author_id' => auth()->id(),
            'published_at' => $validated['status'] === 'published' ? now() : null
        ]);

        // Kategorileri ekle
        if (isset($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Article created successfully',
            'data' => $article->load(['author', 'categories', 'comments'])
        ], 201);
    }

    /**
     * Display the specified article.
     */
    public function show(Article $article)
    {
        return response()->json([
            'status' => 'success',
            'data' => $article->load(['author', 'categories', 'comments'])
        ]);
    }

    /**
     * Update the specified article.
     */
    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'content' => 'sometimes|string',
            'featured_image' => 'nullable|image|max:2048',
            'excerpt' => 'nullable|string',
            'status' => 'sometimes|in:draft,published,archived',
            'categories' => 'array',
            'categories.*' => 'exists:article_categories,id',
            'published_at' => 'nullable|date'
        ]);

        // Yeni resim yükleme
        if ($request->hasFile('featured_image')) {
            // Eski resmi sil
            if ($article->featured_image) {
                Storage::disk('public')->delete($article->featured_image);
            }
            
            $path = $request->file('featured_image')->store('articles', 'public');
            $validated['featured_image'] = $path;
        }

        // Eğer makale yayınlanıyorsa published_at'i güncelle
        if (isset($validated['status']) && $validated['status'] === 'published' && $article->status !== 'published') {
            $validated['published_at'] = now();
        }

        $article->update($validated);

        // Kategorileri güncelle
        if (isset($validated['categories'])) {
            $article->categories()->sync($validated['categories']);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Article updated successfully',
            'data' => $article->load(['author', 'categories', 'comments'])
        ]);
    }

    /**
     * Remove the specified article.
     */
    public function destroy(Article $article)
    {
        // Resmi sil
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }

        $article->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Article deleted successfully'
        ]);
    }

    /**
     * Get articles by category.
     */
    public function byCategory($categorySlug)
    {
        $category = ArticleCategory::where('slug', $categorySlug)->firstOrFail();
        
        $articles = $category->articles()
            ->with(['author', 'categories'])
            ->published()
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'category' => $category,
                'articles' => $articles
            ]
        ]);
    }

    /**
     * Display a listing of public articles.
     */
    public function publicIndex()
    {
        $articles = Article::where('status', 'published')->get();
        return response()->json([
            'status' => 'success',
            'data' => $articles
        ]);
    }

    /**
     * Display the specified article for public view
     */
    public function publicShow(string $id)
    {
        $article = Article::with(['author', 'categories'])->findOrFail($id);
        
        return response()->json([
            'status' => 'success',
            'data' => $article
        ]);
    }
}
