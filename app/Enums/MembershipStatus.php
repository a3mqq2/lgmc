<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case Active = "active";
    case InActive = "inactive";
    case Pending = "pending";
    case InitApprove = "init_approve";
    case Rejected = "rejected";
    public function label(): string
    {
        return match($this) {
            self::Active => 'مفعل',
            self::InActive => 'غير مفعل',
            self::Pending => 'قيد الانتظار',
            self::InitApprove => 'موافقة مبدئية',
            self::Rejected => "مرفوض",
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active => 'bg-success',
            self::InActive => 'bg-danger',
            self::Pending => 'bg-warning',
            self::InitApprove => 'bg-info',
            self::Rejected => 'bg-danger',
        };
    }
}