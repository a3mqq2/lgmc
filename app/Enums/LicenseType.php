<?php

namespace App\Enums;

enum LicenseType: string
{
    case New = 'new';
    case Renewal = 'renewal';
    case Penalty = 'penalty';

    public function label(): string
    {
        return match($this) {
            self::New => 'إذن جديد',
            self::Renewal => 'تجديد',
            self::Penalty => 'غرامة',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::New => 'bg-primary',
            self::Renewal => 'bg-warning text-dark',
            self::Penalty => 'bg-danger',
        };
    }
}
