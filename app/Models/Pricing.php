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


    public function scopeMembershipForDoctor($query, \App\Models\Doctor $doctor)
    {
        // نستدعي الـ accessor اللي أنشأناه للصفة النهائية
        $displayRank = $doctor->doctor_rank->name; 

        return $query
            ->where('type', 'membership')
            ->where('entity_type', 'doctor')
            ->where('doctor_type', $doctor->type->value)
            ->where('name', $displayRank);
    }
}
