<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfectiousDisease extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'disease',
        'patient_type',
        'ward_id',
        'user_id',
        'total',
        'notes',
    ];

    /**
     * Get the user who created the record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the ward where the patient is located.
     */
    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    /**
     * List of possible infectious diseases
     */
    public static function diseaseTypes(): array
    {
        return [
            'Influenza A',
            'Influenza B',
            'Measles',
            'Chicken Pox',
            'TB',
            'Typhoid',
            'Cholera',
            'Rota Virus',
            'RSV',
            'Covid 19',
            'Dengue'
        ];
    }

    /**
     * List of patient types
     */
    public static function patientTypes(): array
    {
        return [
            'adult' => 'Adult',
            'paed' => 'Pediatric',
            'neonate' => 'Neonate'
        ];
    }
}
