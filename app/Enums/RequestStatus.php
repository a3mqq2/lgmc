<?php

namespace App\Enums;

enum RequestStatus: string
{
    case pending = "pending";
    case rejected = "rejected";
    case under_process = "under_process";
    case done = "done";
    case canceled = "canceled";

    public function label(): string
    {
        return match ($this) {
            self::pending => 'قيد الانتظار',
            self::rejected => 'مرفوض',
            self::under_process => 'قيد المعالجة',
            self::done => 'مكتمل',
            self::canceled => 'ملغي',
        };
    }

    public function badgeClass(): string
    {
        return match ($this) {
            self::pending => 'bg-warning',
            self::rejected => 'bg-danger',
            self::under_process => 'bg-info',
            self::done => 'bg-success',
            self::canceled => 'bg-secondary',
        };
    }


    public function is($status): bool
    {
        return $this->value === $status;
    }
}
