<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyData extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'ward_id',
        'user_id',
        'date',
        'death',
        'neonatal_jaundice',
        'bedridden_case',
        'incident_report',
        'remarks',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get the ward that the daily data belongs to.
     */
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Get the user that created the daily data.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
