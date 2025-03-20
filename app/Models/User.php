<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Check if the user is an administrator.
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return $this->username === 'admin';
    }

    /**
     * Get the wards associated with the user.
     */
    public function wards(): BelongsToMany
    {
        return $this->belongsToMany(Ward::class);
    }

    /**
     * Get the ward entries created by the user.
     */
    public function wardEntries(): HasMany
    {
        return $this->hasMany(WardEntry::class);
    }
}
