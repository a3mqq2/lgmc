<?php

namespace App\Enums;

enum GenderEnum: string
{
    case male = "male";
    case female = "female";

    public function label(): string
    {
        return match($this) {
            self::male => 'ذكر',
            self::female => 'انثى',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::male => 'bg-primary',
            self::female => 'bg-primary',
        };
    }
}