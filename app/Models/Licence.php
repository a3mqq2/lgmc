<?php

namespace App\Models;

use App\Enums\DoctorType;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        'amount',
        'doctor_type',
        'medical_facility_id',
    ];

    protected $casts = [
        "doctor_type" => DoctorType::class,
    ];





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

    public function MedicalFacility()
    {
        return $this->belongsTo(MedicalFacility::class, 'medical_facility_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function licensable()
    {
        return $this->morphTo();
    }

    protected static function booted(): void
    {
        static::creating(function (Licence $licence) {
            if (!$licence->code) {  
                $licence->assignSequence();
            }
        });
    }
    

    public function assignSequence(): void
    {
        $year = optional($this->issued_date)->format('Y') ?? now()->year;
        $type = $this->licensable_type;
        $prefix = $type === Doctor::class ? 'LIC' : 'PERM';
    
        $nextIndex = self::where('branch_id', $this->branch_id)
            ->whereYear('issued_date', $year)
            ->where('licensable_type', $type)
            ->max('index') + 1;
    
        $this->index = $nextIndex;
        $this->code  = $this->branch->code
                      . '-' . $prefix . '-'
                      . $year . '-'
                      . str_pad($nextIndex, 3, '0', STR_PAD_LEFT);
    }
    

    public function regenerateCode(): void
    {
        DB::transaction(function () {
            $this->assignSequence();
            $this->saveQuietly();
        });
    }
}
