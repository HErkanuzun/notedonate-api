<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'university_id',
        'code',
        'description',
        'head_of_department',
        'website'
    ];

    /**
     * Get the university that owns the department.
     */
    public function university()
    {
        return $this->belongsTo(University::class);
    }

    /**
     * Get the students for the department.
     */
    public function students()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the notes for the department.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get the exams for the department.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
