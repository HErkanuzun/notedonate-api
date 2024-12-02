<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\University;
use App\Models\Department;

class Note extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'storage_link',
        'viewer',
        'like',
        'user_id',
        'university_id',
        'department_id',
        'year',
        'semester'
    ];

    protected $casts = [
        'viewer' => 'integer',
        'like' => 'integer',
        'year' => 'integer'
    ];

    /**
     * Get the user that owns the note.
     */
    public function user()
    {
        return $this->belongsTo(User::class,"created_by");
    }

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
