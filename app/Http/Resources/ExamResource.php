<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ExamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'type' => 'exam',
            'id' => $this->id,
            'attributes' => [
                'title' => $this->title,
                'description' => $this->description,
                'total_marks' => $this->total_marks,
                'duration' => $this->duration,
                'status' => $this->status,
                'subject' => $this->subject,
                'cover_image' => $this->cover_image,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,
                'university' => $this->user?->university ?? 'Unknown University',
                'department' => $this->user?->department ?? 'Unknown Department',
                'author' => $this->user?->name ?? 'Anonymous',
                'questions_count' => $this->questions->count(),
            ]
        ];
    }
}
