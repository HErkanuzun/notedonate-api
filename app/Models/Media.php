<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'file_path',
        'type',
        'size',
        'description',
    ];

    /**
     * Get all of the owning mediaable models.
     */
    public function mediaable()
    {
        return $this->morphTo();
    }
}
