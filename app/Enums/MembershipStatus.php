<?php

namespace App\Enums;

enum MembershipStatus: string
{
    case Active         = 'active';
    case InActive       = 'inactive';
    case Pending        = 'pending';
    case UnderPayment   = 'under_payment';
    case InitApprove    = 'init_approve';
    case UnderApprove   = 'under_approve';
    case under_edit     = 'under_edit';
    case under_proccess = 'under_proccess';
    case Rejected       = 'rejected';
    case Banned         = 'banned';
    case UnderComplete = 'under_complete';
    case Expired        = 'expired';

    public function label(): string
    {
        return match($this) {
            self::Active         => 'مفعل',
            self::InActive       => 'غير مفعّل',
            self::Pending        => 'قيد الانتظار',
            self::InitApprove    => 'موافقة مبدئية',
            self::UnderPayment   => 'قيد الدفع',
            self::UnderApprove   => 'قيد الموافقة',
            self::under_edit     => 'قيد التعديل',
            self::under_proccess => 'قيد المعالجة',
            self::Rejected       => 'مرفوض',
            self::Banned         => 'محظور',
            self::UnderComplete  => 'قيد الاكتمال',
            self::Expired        => 'منتهي الصلاحية',
        };
    }

    public function badgeClass(): string
    {
        return match($this) {
            self::Active         => 'bg-success',
            self::InActive       => 'bg-danger',
            self::Pending        => 'bg-warning',
            self::InitApprove    => 'bg-info',
            self::UnderPayment   => 'bg-warning',
            self::UnderApprove   => 'bg-warning',
            self::under_edit     => 'bg-info',
            self::under_proccess => 'bg-info',
            self::Rejected       => 'bg-danger',
            self::Banned         => 'bg-danger',
            self::UnderComplete  => 'bg-info',
            self::Expired        => 'bg-secondary',
        };
    }
}
