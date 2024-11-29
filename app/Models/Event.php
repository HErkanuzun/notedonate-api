<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'type',
        'status',
        'created_by',
        'university_id',
        'department_id',
        'year',
        'semester'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'year' => 'integer'
    ];

    /**
     * Get the user that created the event.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get all comments for the event.
     */
    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    /**
     * Get the university that owns the event.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the department that owns the event.
     */
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
