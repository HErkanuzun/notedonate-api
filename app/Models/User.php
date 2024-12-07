<?php

namespace App\Models;

use App\Notifications\CustomVerifyEmail;
use App\Traits\HasStorage;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Note;
use App\Models\Exam;
use App\Models\Article;
use App\Models\Event;
use App\Models\Comment;
use App\Models\Storage;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasStorage;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'bio',
        'university',
        'department',
        'role',
        'avatar_url'
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
        return $this->hasMany(Article::class, 'author_id');
    }

    /**
     * Get all events created by the user.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get all comments made by the user.
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get all files for the user.
     */
    public function files()
    {
        return $this->belongsToMany(Storage::class, 'user_storage')
                    ->withPivot('type')
                    ->withTimestamps();
    }

    /**
     * Get user's avatar.
     */
    public function avatar()
    {
        return $this->belongsToMany(Storage::class, 'user_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'avatar')
                    ->withTimestamps()
                    ->latest()
                    ->first();
    }

    /**
     * Get user's documents.
     */
    public function documents()
    {
        return $this->belongsToMany(Storage::class, 'user_storage')
                    ->withPivot('type')
                    ->wherePivot('type', 'document')
                    ->withTimestamps();
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

    public function followers()
    {
        return $this->belongsToMany(User::class, 'followers', 'following_id', 'follower_id');
    }

    public function following()
    {
        return $this->belongsToMany(User::class, 'followers', 'follower_id', 'following_id');
    }
}
