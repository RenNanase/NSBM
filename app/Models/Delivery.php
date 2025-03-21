<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'ward_id',
        'user_id',
        'report_date',
        'svd',
        'lscs',
        'vacuum',
        'forceps',
        'breech',
        'eclampsia',
        'twin',
        'mrp',
        'fsb_mbs',
        'bba',
        'total',
        'notes',
    ];

    protected $casts = [
        'report_date' => 'date',
        'svd' => 'integer',
        'lscs' => 'integer',
        'vacuum' => 'integer',
        'forceps' => 'integer',
        'breech' => 'integer',
        'eclampsia' => 'integer',
        'twin' => 'integer',
        'mrp' => 'integer',
        'fsb_mbs' => 'integer',
        'bba' => 'integer',
        'total' => 'integer',
    ];

    /**
     * Get the ward that this delivery report belongs to.
     */
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * Get the user who created the delivery report.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Calculate the total automatically before saving
     */
    protected static function booted()
    {
        static::saving(function ($delivery) {
            $delivery->total =
                $delivery->svd +
                $delivery->lscs +
                $delivery->vacuum +
                $delivery->forceps +
                $delivery->breech +
                $delivery->eclampsia +
                $delivery->twin +
                $delivery->mrp +
                $delivery->fsb_mbs +
                $delivery->bba;
        });
    }
}
