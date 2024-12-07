<?php

namespace App\Models;

use App\Traits\HasStorage;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamQuestion;
use App\Models\User;
use App\Models\Comment;
use App\Models\Media;
use App\Models\University;

class Exam extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory, HasStorage;

    protected $fillable = [
        'title',
        'description',
        'total_marks',
        'duration',
        'user_id',
        'status',
        'subject',
        'cover_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    /**
     * Get all comments for the exam.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }
}
