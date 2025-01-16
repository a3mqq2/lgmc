<?php

namespace App\Enums;

enum Priority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';

    /**
     * Get the Arabic label for the priority.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Low => 'منخفض',
            self::Medium => 'متوسط',
            self::High => 'مرتفع',
        };
    }

    /**
     * Get the CSS badge class for the priority.
     *
     * @return string
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Low => 'bg-low',
            self::Medium => 'bg-medium',
            self::High => 'bg-high',
        };
    }
}
