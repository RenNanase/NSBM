<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WardEntry extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'ward_id',
        'shift_id',
        'cf_patient',
        'total_patient',
        'licensed_bed_bor',
        'total_bed_bor',
        'total_admission',
        'total_transfer_in',
        'total_transfer_out',
        'total_discharge',
        'aor',
        'total_staff_on_duty',
        'overtime',
        'total_daily_patients',
    ];

    /**
     * Get the user that created the ward entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ward that the entry belongs to.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Get the shift that the entry belongs to.
     */
    public function shift(): BelongsTo
    {
        return $this->belongsTo(Shift::class);
    }
}
