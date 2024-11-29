<?php

namespace App\Http\Controllers;

use App\Models\ArticleCategory;
use Illuminate\Http\Request;

class ArticleCategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     */
    public function index()
    {
        $categories = ArticleCategory::withCount('articles')
            ->orderBy('name')
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:article_categories,name',
            'description' => 'nullable|string'
        ]);

        $category = ArticleCategory::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
            'data' => $category
        ], 201);
    }

    /**
     * Display the specified category.
     */
    public function show(ArticleCategory $category)
    {
        return response()->json([
            'status' => 'success',
            'data' => $category->load(['articles' => function($query) {
                $query->published()->orderBy('published_at', 'desc');
            }])
        ]);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, ArticleCategory $category)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:article_categories,name,' . $category->id,
            'description' => 'nullable|string'
        ]);

        $category->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
            'data' => $category
        ]);
    }

    /**
     * Remove the specified category.
     */
    public function destroy(ArticleCategory $category)
    {
        // Kategoriye ait makalelerin kategorisini kaldır
        $category->articles()->detach();
        
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully'
        ]);
    }
}
