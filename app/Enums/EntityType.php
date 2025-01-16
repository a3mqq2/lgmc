<?php

namespace App\Enums;

enum EntityType: string
{
    case Doctor = 'doctor';
    case MedicalFacility = 'medical_facility';

    /**
     * Get a human-readable label for each entity type.
     */
    public function label(): string
    {
        return match($this) {
            self::Doctor => 'طبيب',
            self::MedicalFacility => 'منشأة طبية',
        };
    }

    /**
     * Get the badge class for styling purposes.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Doctor => 'badge bg-secondary',
            self::MedicalFacility => 'badge bg-warning',
        };
    }
}
