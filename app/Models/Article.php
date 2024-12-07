<?php

namespace App\Models;

use App\Traits\HasStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Article extends Model
{
    /** @use HasFactory<\Database\Factories\ArticleFactory> */
    use HasFactory, SoftDeletes, HasStorage;

    protected $fillable = [
        'title',
        'content',
        'featured_image',
        'excerpt',
        'status',
        'author_id',
        'published_at'
    ];

    protected $casts = [
        'published_at' => 'datetime'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($article) {
            $article->slug = $article->createUniqueSlug($article->title);
        });
    }

    /**
     * Create a unique slug.
     */
    private function createUniqueSlug($title)
    {
        $slug = Str::slug($title);
        $count = static::where('slug', $slug)->orWhere('slug', 'LIKE', $slug . '-%')->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get the author of the article.
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the categories for the article.
     */
    public function categories()
    {
        return $this->belongsToMany(ArticleCategory::class, 'article_category', 'article_id', 'category_id')
                    ->withTimestamps();
    }

    /**
     * Get all comments for the article.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all media for the article.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    /**
     * Scope a query to only include published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope a query to only include draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }
}
