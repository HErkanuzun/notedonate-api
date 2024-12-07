<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'type',
        'status',
        'created_by'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    /**
     * Get the user who created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all files for the event.
     */
    public function files()
    {
        return $this->belongsToMany(Storage::class, 'event_storage')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Get only image files.
     */
    public function images()
    {
        return $this->belongsToMany(Storage::class, 'event_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'image')
                    ->withTimestamps();
    }

    /**
     * Get only document files.
     */
    public function documents()
    {
        return $this->belongsToMany(Storage::class, 'event_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'document')
                    ->withTimestamps();
    }

    /**
     * Get all media for the event.
     */
    public function media()
    {
        return $this->morphMany(Media::class, 'mediaable');
    }
}
