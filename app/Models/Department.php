<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['university_id', 'name', 'slug', 'is_active'];

    public function university()
    {
        return $this->belongsTo(University::class);
    }

    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    public function exams()
    {
        return $this->hasMany(Exam::class);
    }
}
