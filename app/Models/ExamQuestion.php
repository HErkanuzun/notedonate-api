<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Exam;

class ExamQuestion extends Model
{
    /** @use HasFactory<\Database\Factories\ExamQuestionFactory> */
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'question',
        'question_type',
        'options',
        'correct_answer',
        'marks'
    ];

    protected $casts = [
        'options' => 'array',
        'marks' => 'integer'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
