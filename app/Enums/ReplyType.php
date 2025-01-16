<?php

namespace App\Enums;

enum ReplyType: string
{
    case Internal = 'internal';
    case External = 'external';

    /**
     * Get an Arabic label for the reply type.
     */
    public function label(): string
    {
        return match ($this) {
            self::Internal => 'داخلي',
            self::External => 'خارجي',
        };
    }

    /**
     * Get a CSS badge class based on the reply type.
     */
    public function badgeClass(): string
    {
        return match ($this) {
            self::Internal => 'bg-dark',
            self::External => 'bg-secondary',
        };
    }
}
