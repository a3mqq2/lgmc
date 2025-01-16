<?php

namespace App\Enums;

enum MaritalStatus: string
{
    case single = "single";
    case married = "married";

    public function label(): string
    {
        return match($this) {
            self::single => 'متزوج',
            self::married => 'اعزب',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::single => 'bg-success',
            self::married => 'bg-secondary',
        };
    }
}
