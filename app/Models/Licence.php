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
        'doctor_type' => DoctorType::class,
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            'under_approve_branch' => "<span class='badge badge-primary bg-primary text-light'>قيد موافقة الفرع</span>",
            'under_approve_admin'  => "<span class='badge badge-primary bg-primary text-light'>قيد موافقة الادارة</span>",
            'under_payment'        => "<span class='badge badge-info bg-info text-light'>قيد المراجعة المالية </span>",
            'revoked'              => "<span class='badge badge-danger bg-danger text-light'> تم ايقافه    </span>",
            'expired'              => "<span class='badge badge-danger bg-danger text-light'>  منتهي الصلاحية     </span>",
            'active'               => "<span class='badge badge-success bg-success text-light'> ساري    </span>",
            default                => "<span class='badge badge-secondary text-light'> غير معروف    </span>",
        };
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    public function logs()
    {
        return $this->hasMany(LicenceLog::class, 'licence_id');
    }

    public function medicalFacility()
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
            DB::transaction(function () use ($licence) {
                if (! $licence->code) {
                    $licence->assignSequence();
                }
            });
        });
    }

    public function assignSequence(): void
    {
        $year   = optional($this->issued_date)->format('Y') ?? now()->year;
        $type   = $this->licensable_type;
        $prefix = $type === Doctor::class ? 'LIC' : 'PERM';

        $nextIndex = self::where('branch_id', $this->branch_id)
            ->where('licensable_type', $type)
            ->where(function ($q) use ($year) {
                $q->whereYear('issued_date', $year)
                  ->orWhere(function ($q2) use ($year) {
                      $q2->whereNull('issued_date')
                         ->whereYear('created_at', $year);
                  });
            })
            ->lockForUpdate()
            ->max('index') + 1;

        do {
            $candidate = $this->branch->code
                . '-' . $prefix . '-'
                . $year . '-'
                . str_pad($nextIndex, 3, '0', STR_PAD_LEFT);

            if (! self::where('code', $candidate)->exists()) {
                break;
            }

            $nextIndex++;
        } while (true);

        $this->index = $nextIndex;
        $this->code  = $candidate;
    }

    public function regenerateCode(): void
    {
        DB::transaction(function () {
            $this->assignSequence();
            $this->saveQuietly();
        });
    }
}
