<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VerificationCode extends Model
{
    protected $fillable = [
        'phone',
        'code',
        'used',
        'expires_at'
    ];

    protected $casts = [
        'used' => 'boolean',
        'expires_at' => 'datetime'
    ];

    public function isValid()
    {
        return !$this->used && now()->lt($this->expires_at);
    }
}
