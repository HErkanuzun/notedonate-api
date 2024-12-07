<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Storage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'file_path',
        'type'
    ];

    /**
     * Get the full URL to the file.
     */
    public function getUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }

    /**
     * Get all notes that use this storage.
     */
    public function notes()
    {
        return $this->belongsToMany(Note::class, 'note_storage')
                    ->withTimestamps();
    }

    /**
     * Get all articles that use this storage.
     */
    public function articles()
    {
        return $this->belongsToMany(Article::class, 'article_storage')
                    ->withTimestamps();
    }

    /**
     * Get all events that use this storage.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_storage')
                    ->withTimestamps();
    }

    /**
     * Get all users that use this storage.
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_storage')
                    ->withTimestamps();
    }
}
