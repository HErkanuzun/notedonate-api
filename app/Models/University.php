<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class University extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'city',
        'website',
        'description',
        'logo'
    ];

    /**
     * Get the departments for the university.
     */
    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    /**
     * Get the students for the university.
     */
    public function students()
    {
        return $this->hasMany(User::class);
    }
}
