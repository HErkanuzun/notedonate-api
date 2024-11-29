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
        'correct_option'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }
}
