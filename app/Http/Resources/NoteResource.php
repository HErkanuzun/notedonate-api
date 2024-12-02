<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NoteResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
            'university' => $this->university,
            'department' => $this->department,
            'year' => $this->year,
            'semester' => $this->semester,
            'subject' => $this->subject,
            'storage_link' => $this->storage_link,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }
}
