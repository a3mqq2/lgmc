<?php

namespace App\Enums;

enum Category: string
{
    case Question = 'question';
    case Suggestion = 'suggestion';

    /**
     * Get the Arabic label for the category.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::Question => 'استفسار',
            self::Suggestion => 'اقتراح',
        };
    }

    /**
     * Get the CSS badge class for the category.
     *
     * @return string
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::Question => 'bg-question',
            self::Suggestion => 'bg-suggestion',
        };
    }
}
