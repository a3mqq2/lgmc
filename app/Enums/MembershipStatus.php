<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case Active = "active";
    case InActive = "inactive";

    public function label(): string
    {
        return match($this) {
            self::Active => 'مفعل',
            self::InActive => 'غير مفعل',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active => 'bg-success',
            self::InActive => 'bg-danger',
        };
    }
}