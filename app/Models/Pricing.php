<?php

namespace App\Models;

use App\Enums\DoctorType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PricingType;
use App\Enums\EntityType;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricings';

    protected $fillable = [
        'name',
        'amount',
        'type',
        'entity_type',
        'doctor_type'
    ];

    protected $casts = [
        'type' => PricingType::class,
        'entity_type' => EntityType::class,
        'doctor_type' => DoctorType::class,
    ];

    /**
     * Get a human-readable label for the pricing type.
     */
    public function getTypeLabelAttribute(): string
    {
        return $this->type->label();
    }

    /**
     * Get a human-readable label for the entity type.
     */
    public function getEntityTypeLabelAttribute(): string
    {
        return $this->entity_type->label();
    }

    /**
     * Get a human-readable label for the doctor type.
     */
    public function getDoctorTypeLabelAttribute(): string
    {
        return $this->doctor_type->label();
    }
}
