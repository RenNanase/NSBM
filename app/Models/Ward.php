<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'total_bed',
        'total_licensed_op_beds',
    ];

    /**
     * Get the users associated with the ward.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get the ward entries for the ward.
     */
    public function wardEntries(): HasMany
    {
        return $this->hasMany(WardEntry::class);
    }

    /**
     * Get the census entries for the ward.
     */
    public function censusEntries(): HasMany
    {
        return $this->hasMany(CensusEntry::class);
    }
}
