<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class University extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'city', 'slug', 'is_active'];

    public function departments()
    {
        return $this->hasMany(Department::class);
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
