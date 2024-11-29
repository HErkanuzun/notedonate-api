<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ArticleCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            $category->slug = $category->createUniqueSlug($category->name);
        });
    }

    /**
     * Create a unique slug.
     */
    private function createUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $count = static::whereRaw("slug REGEXP '^{$slug}(-[0-9]+)?$'")->count();
        
        return $count ? "{$slug}-{$count}" : $slug;
    }

    /**
     * Get the articles for the category.
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_category', 'category_id', 'article_id')
                    ->withTimestamps();
    }
}
