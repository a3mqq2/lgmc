<?php

namespace App\Enums;

enum Department: string
{
    case Finance = 'finance';
    case Operation = 'operation';
    case Management = 'management';

    /**
     * Get the Arabic label for the department.
     */
    public function label(): string
    {
        return match($this) {
            self::Finance => 'المالية',
            self::Operation => 'العمليات',
            self::Management => 'الإدارة',
            self::IT => 'تقنية المعلومات',
        };
    }

    /**
     * Get the CSS badge class for the department.
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Finance => 'bg-finance',
            self::Operation => 'bg-operation',
            self::Management => 'bg-management',
            self::IT => 'bg-it',
        };
    }

    /**
     * Find an enum case by its string value.
     * Returns the enum case if it exists, or null if it doesn't.
     */
    public static function find(string $value): ?self
    {
        return self::tryFrom($value);
    }
}
