<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamQuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'exam' => [
                'id' => $this->exam->id,
                'name' => $this->exam->name,
            ],
            'question' => $this->question,
            'question_type' => $this->question_type,
            'options' => $this->options,
            'correct_answer' => $this->correct_answer,
            'marks' => $this->marks,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
