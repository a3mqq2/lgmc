<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Licence extends Model
{
    protected $fillable = [
        'doctor_id',
        'medical_facility_id',
        'index',
        'code',
        'issued_date',
        'expiry_date',
        'status',
        'doctor_type',
        'doctor_rank_id',
        'created_by',
        'amount',
        'institution_id',
    ];

    protected $casts = [
        'issued_date' => 'date',
        'expiry_date' => 'date',
        'amount'      => 'decimal:2',
        'status'      => \App\Enums\MembershipStatus::class,
    ];

    protected static function booted()
    {
        static::creating(function (Licence $licence) {
            $year  = Carbon::now()->year;
            $short = $licence->doctor_type
                        ? strtoupper(substr($licence->doctor_type, 0, 3))
                        : 'MF';

            // حدِّد نمط الكود المطلوب البحث عنه
            $pattern = $licence->doctor_type && $licence->doctor_type === 'libyan'
                        ? "{$year}-%"            // مثال: 2025-1
                        : "{$short}-{$year}-%";   // مثال: MF-2025-1 أو VIS-2025-1

            // احسب آخر index بناءً على الكود نفسه
            $lastIndex = self::where('code', 'like', $pattern)->max('index') ?? 0;
            $licence->index = $lastIndex + 1;

            // أنشئ الكود النهائي
            if ($licence->doctor_type && $licence->doctor_type === 'libyan') {
                $licence->code = "{$year}-{$licence->index}";
            } else {
                $licence->code = "{$short}-{$year}-{$licence->index}";
            }
        });
    }

    /* ---------- العلاقات ---------- */

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function medicalFacility(): BelongsTo
    {
        return $this->belongsTo(MedicalFacility::class);
    }

    public function doctorRank(): BelongsTo
    {
        return $this->belongsTo(DoctorRank::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function workIn(): BelongsTo
    {
        return $this->belongsTo(MedicalFacility::class, 'workin_medical_facility_id');
    }


    public function institution()
    {
        return $this->belongsTo(Institution::class, 'institution_id');
    }

    public function doctor_rank() { return $this->belongsTo(DoctorRank::class); }
    public function specialty()   { return $this->belongsTo(Specialty::class, 'specialty_id'); }
}
