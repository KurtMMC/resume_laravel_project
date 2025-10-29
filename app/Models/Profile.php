<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Profile extends Model
{
    protected $fillable = [
    'user_id', 'name', 'title', 'address', 'phone', 'email', 'slug', 'profile_picture',
        'experiences', 'education', 'skills', 'socials', 'is_public',
    ];

    protected $casts = [
        'experiences' => 'array',
        'education'   => 'array',
        'skills'      => 'array',
        'socials'     => 'array',
        'is_public'   => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
