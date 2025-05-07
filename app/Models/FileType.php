<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'name',
        'is_required',
        'doctor_type',
        'for_registration',
        'facility_type',
    ];

    public function getFileTypeArAttribute()
    {
        return $this->type === "doctor" ? "طبيب" : "منشأة طبية";
    }

    public function getIsForRegistrationLabelAttribute()
    {
        return $this->for_registration ? '✅' : '❌';
    }

    public function doctorRank()
    {
        return $this->belongsTo(DoctorRank::class);
    }
}
