<?php

namespace App\Enums;

enum DoctorType: string
{
    case Libyan = 'libyan';
    case Foreign = 'foreign';
    case Visitor = 'visitor';
    case Palestinian = 'palestinian';

    public function label(): string
    {
        return match($this) {
            self::Libyan => 'ليبي',
            self::Foreign => 'أجنبي',
            self::Visitor => 'زائر',
            self::Palestinian => 'فلسطيني',
        };
    }


    // If you wish to have badges for doctor types as well, add a badge class:
    public function badgeClass(): string
    {
        return match($this) {
            self::Libyan => 'bg-gradient-cyan  text-light',
            self::Foreign => 'bg-gradient-light text-dark',
            self::Visitor => 'bg-gradient-warning  text-light',
            self::Palestinian => 'bg-gradient-purple text-light',
        };
    }
}
