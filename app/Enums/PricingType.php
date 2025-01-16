<?php

namespace App\Enums;

enum PricingType: string
{
    case Membership = 'membership';
    case License = 'license';
    case Service = 'service';
    case Penalty = 'penalty';

    /**
     * Get a human-readable label for each type.
     */
    public function label(): string
    {
        return match($this) {
            self::Membership => 'عضوية',
            self::License => 'اذن مزاولة',
            self::Service => 'خدمة',
            self::Penalty => 'غرامة',
        };
    }

    /**
     * Get the badge class for styling purposes.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Membership => 'badge bg-primary',
            self::License => 'badge bg-success',
            self::Service => 'badge bg-info',
            self::Penalty => 'badge bg-danger',
        };
    }
}
