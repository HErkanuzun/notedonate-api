<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ExamQuestion;
use App\Models\User;
use App\Models\Comment;

class Exam extends Model
{
    /** @use HasFactory<\Database\Factories\ExamFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'total_marks',
        'duration',
        'created_by',
        'status'
    ];

    public function questions()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function examQuestion()
    {
        return $this->hasMany(ExamQuestion::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all comments for the exam.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
