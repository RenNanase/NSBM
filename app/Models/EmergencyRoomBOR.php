<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmergencyRoomBOR extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'emergency_room_bor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'date',
        'shift',
        'green',
        'yellow',
        'red',
        'hours_24_census',
        'grand_total',
        'ambulance_call',
        'admission',
        'transfer',
        'death',
        'user_id',
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
     * Calculate the grand total from the sum of green, yellow, and red categories.
     */
    public function calculateGrandTotal()
    {
        return $this->green + $this->yellow + $this->red;
    }

    /**
     * Get the user that created the BOR entry.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
