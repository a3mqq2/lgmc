<?php

namespace App\Enums;

enum Status: string
{
    case New = 'new';
    case Pending = 'pending';
    case CustomerReply = 'customer_reply';
    case Complete = 'complete';
    case UserReply = 'user_reply';

    /**
     * Get the Arabic label for the status.
     *
     * @return string
     */
    public function label(): string
    {
        return match($this) {
            self::New => 'جديد',
            self::Pending => 'معلق',
            self::CustomerReply => 'رد المستحق',
            self::Complete => 'مكتمل',
            self::UserReply => 'رد المستخدم',
        };
    }

    /**
     * Get the CSS badge class for the status.
     *
     * @return string
     */
    public function badgeClass(): string
    {
        return match($this) {
            self::New => 'bg-new',
            self::Pending => 'bg-pending',
            self::CustomerReply => 'bg-customer-reply',
            self::Complete => 'bg-complete',
            self::UserReply => 'bg-user-reply',
        };
    }
}
