<?php

namespace App\Enums;

enum InvoiceStatus: string
{
    case paid = 'paid';
    case unpaid = 'unpaid';
    case relief = "relief";

    public function label(): string
    {
        return match($this) {
            self::paid => 'مدفوعة',
            self::unpaid => 'غير مدفوعة',
            self::relief => 'معفي عنه',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::paid => 'bg-success',
            self::unpaid => 'bg-danger',
            self::relief => "bg-warning",
        };
    }
}
