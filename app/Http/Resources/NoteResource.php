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
            'type'=> 'note',
            'links'=> [
                'self'=> env('APP_URL')
            ],
            'id'=> $this->id,
            'attributes'=> [
                'title'=>$this->title,
                'content'=>$this->content,
                'created_at'=>$this->created_at,
                'storage_link'=>$this->storage_link,
            ]
            ];
    }
}
