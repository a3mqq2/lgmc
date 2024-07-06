<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'name_en',
        'national_number',
        'mother_name',
        'country_id',
        'date_of_birth',
        'marital_status',
        'gender',
        'passport_number',
        'passport_expiration',
        'address',
        'phone',
        'phone_2',
        'email',
        'hand_graduation_id',
        'internership_complete',
        'academic_degree_id',
        'qualification_university_id',
        'qualification_date',
        'certificate_of_excellence',
        'graduationـcertificate',
        'passport',
        'id_card',
        'employeer_message',
        'id_number',
        'birthـcertificate',
        'personal_photo',
        'work_visa',
        'health_certificate',
        'jobـcontract',
        'anotherـcertificate',
        'capacity_id',
        'ex_medical_facilities',
        'experience',
        'notes',
        'branch_id',
        'specialty_1_id',
        'specialty_2_id',
        'specialty_3_id',
        'certificate_of_excellence_date'
    ];

    protected $casts = [
        'date_of_birth' => 'datetime',
        'passport_expiration' => 'datetime',
        'internership_complete' => 'datetime',
        'qualification_date' => 'datetime',
        'certificate_of_excellence_date' => "datetime",
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function handGraduation()
    {
        return $this->belongsTo(University::class, 'hand_graduation_id');
    }

    public function academicDegree()
    {
        return $this->belongsTo(AcademicDegree::class);
    }

    public function qualificationUniversity()
    {
        return $this->belongsTo(University::class, 'qualification_university_id');
    }

    public function specialty1()
    {
        return $this->belongsTo(Specialty::class, 'specialty_1_id');
    }

    public function specialty2()
    {
        return $this->belongsTo(Specialty::class, 'specialty_2_id');
    }

    public function specialty3()
    {
        return $this->belongsTo(Specialty::class, 'specialty_3_id');
    }

    public function capacity()
    {
        return $this->belongsTo(Capacity::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function medicalFacilities()
    {
        return $this->belongsToMany(MedicalFacility::class);
    }

    public function files()
    {
        return $this->hasMany(DoctorFile::class);
    }


    public function licenses()
    {
        return $this->hasMany(Licence::class, 'licensable_id')->where('licensable_type', 'App\Models\Doctor');
    }
    
}
