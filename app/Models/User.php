<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Note;
use App\Models\Exam;
use App\Models\Article;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile_photo_url',
        'bio',
        'university',
        'department',
        'followers',
        'following',
        'favorites'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'favorites' => 'array',
        'password' => 'hashed',
    ];

    /**
     * Get all notes created by the user.
     */
    public function notes()
    {
        return $this->hasMany(Note::class);
    }

    /**
     * Get all exams created by the user.
     */
    public function exams()
    {
        return $this->hasMany(Exam::class);
    }

    /**
     * Get all articles created by the user.
     */
    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    /**
     * Get the default profile photo URL if no custom photo is uploaded.
     */
    protected static function getDefaultProfilePhotoUrl($name)
    {
        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Get the user's profile photo URL.
     */
    public function getProfilePhotoUrlAttribute($value)
    {
        return $value ?? self::getDefaultProfilePhotoUrl($this->name);
    }

    /**
     * Send the email verification notification.
     *
     * @return void
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new CustomVerifyEmail);
    }
}
