<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'ownership',
        'medical_facility_type_id',
        'branch_id',
        'address',
        'phone_number',
        'user_id',
        'commerical_number',
    ];

    public function type()
    {
        return $this->belongsTo(MedicalFacilityType::class, 'medical_facility_type_id');
    }


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function doctors()
    {
        return $this->belongsToMany(Doctor::class);
    }

    public function branch() {
        return $this->belongsTo(Branch::class);
    }

    public function medicalFacilityType() {
        return $this->belongsTo(MedicalFacilityType::class);
    }
    

    public function files() {
        return $this->hasMany(MedicalFacilityFile::class);
    }
}
