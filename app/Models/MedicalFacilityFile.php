<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFacilityFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_facility_id',
        'file_name',
        'file_path',
        'uploaded_at',
        "file_type_id",
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function medicalFacility()
    {
        return $this->belongsTo(MedicalFacility::class);
    }

    public function fileType() {
        return $this->belongsTo(FileType::class);
    }
}
