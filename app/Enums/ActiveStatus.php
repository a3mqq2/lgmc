<?php

namespace App\Enums;

enum ActiveStatus: int
{
    case Active = 1;
    case Inactive = 0;

    public function label(): string
    {
        return match($this) {
            self::Active => 'فعال',
            self::Inactive => 'غير فعال',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active => 'bg-success',
            self::Inactive => 'bg-secondary',
        };
    }
}
