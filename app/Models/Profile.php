<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'bio',
        'location',
        'birth_date',
        'phone_number',
        'website',
        'social_media_links',
        'occupation'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
