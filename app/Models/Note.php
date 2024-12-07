<?php

namespace App\Models;

use App\Traits\HasStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Comment;
use App\Models\User;
use App\Models\University;
use App\Models\Department;
use App\Models\Storage;
use App\Models\Media;

class Note extends Model
{
    use HasFactory, SoftDeletes, HasStorage;

    protected $fillable = [
        'title',
        'content',
        'university_id',
        'department_id',
        'year',
        'semester',
        'subject',
        'user_id',
        'category',
        'status',
        'cover_image',
        'file_path',
        'storage_path'
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the university that the note belongs to.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the department that the note belongs to.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    /**
     * Get all comments for the note.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get all files for the note.
     */
    public function files()
    {
        return $this->belongsToMany(Storage::class, 'note_storage')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Get only document files.
     */
    public function documents()
    {
        return $this->belongsToMany(Storage::class, 'note_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'document')
                    ->withTimestamps();
    }

    /**
     * Get only image files.
     */
    public function images()
    {
        return $this->belongsToMany(Storage::class, 'note_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'image')
                    ->withTimestamps();
    }

    /**
     * Get all media for the note.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
