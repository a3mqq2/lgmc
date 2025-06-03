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
            $year = Carbon::now()->year;
            $lastIndex = self::whereYear('issued_date', $year)->max('index') ?? 0;
            $licence->index = $lastIndex + 1;
            $short = !$licence->doctor_type ? 'MF' :   strtoupper(substr($licence->doctor_type, 0, 3));
            if($licence->doctor_type && $licence->doctor_type == "libyan")
            {
                $licence->code = "{$year}-{$licence->index}";

            } else {
                $licence->code = "{$short}-{$year}-{$licence->index}";
            }
        });
    }

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

    public function doctor_rank()         { return $this->belongsTo(DoctorRank::class); }
    public function specialty()          { return $this->belongsTo(Specialty::class, 'specialty_id'); }

}
