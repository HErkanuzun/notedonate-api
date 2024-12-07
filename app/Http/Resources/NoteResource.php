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
                'cover_image'=> $this->cover_image ?: 'https://images.unsplash.com/photo-1623074716850-ba4c90d49f2f?q=80&w=2598&auto=format&fit=crop&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D',
                'category'=>$this->category,
                'status'=>$this->status,
                'file_path'=>$this->file_path,
                'user_id'=>$this->user_id,
                'author'=> $this->user?->name ?? 'Anonymous',
                'university'=> $this->user?->university ?? 'Unknown University',
                'department'=> $this->user?->department ?? 'Unknown Department',
                'subject'=> $this->category ?? 'General',
                'semester'=> '2023-2024',
                'year'=> '2024',
                'likes'=> 0,
                'downloads'=> 0
            ]
        ];
    }
}
