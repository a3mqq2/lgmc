<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Licence extends Model
{
    use HasFactory;

    protected $fillable = [
        'licensable_id',
        'licensable_type',
        'issued_date',
        'expiry_date',
        'status',
        'doctor_id',
        'branch_id',
        'created_by',
        'amount'
    ];

    /**
     * Get the owning licensable model.
     */
    public function licensable()
    {
        return $this->morphTo();
    }

    /**
     * Get the doctor associated with the licence.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getStatusBadgeAttribute() {
        $status = "";
        switch ($this->status) {
            case 'under_approve_branch':
                $status = "<span class='badge badge-primary bg-primary text-light'>قيد موافقة الفرع</span>";
                break;
            case 'under_approve_admin':
                $status =  "<span class='badge badge-primary bg-primary text-light'>قيد موافقة الادارة</span>";
                break;
            case 'under_payment':
                $status = "<span class='badge badge-info bg-info text-light'>قيد المراجعة المالية </span>";
                break;
            case 'revoked':
                $status = "<span class='badge badge-danger bg-danger text-light'> تم ايقافه    </span>";
                break;
            case 'expired':
                $status =  "<span class='badge badge-danger bg-danger text-light'>  منتهي الصلاحية     </span>";
                break;
            case 'active':
                $status =  "<span class='badge badge-success bg-success text-light'> ساري    </span>";
                break;
            
            default:
                $status = "<span class='badge badge-secondary text-light'> غير معروف    </span>";
                break;
        }

        return $status;
    }

    public function createdBy() {
        return $this->belongsTo(User::class);
    }

    public function logs() {
        return $this->hasMany(LicenceLog::class, 'licence_id');
    }
}
