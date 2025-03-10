<?php

namespace App\Models;

use App\Enums\DoctorType;
use App\Enums\GenderEnum;
use App\Enums\MaritalStatus;
use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Doctor extends Authenticatable
{
    use HasFactory,Notifiable;

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
        'doctor_rank_id',
        'ex_medical_facilities',
        'experience',
        'notes',
        'branch_id',
        'specialty_1_id',
        'specialty_2_id',
        'specialty_3_id',
        'certificate_of_excellence_date',
        'doctor_number',
        'country_graduation_id',
        'type',
        'due',
        'membership_status',
        'membership_expiration_date',
        'password',
        'visiting_date',
        'registered_at',
        'institution_id',
    ];





    protected $hidden = [
        'remember_token',
    ];


    protected $casts = [
        'date_of_birth' => 'datetime',
        'passport_expiration' => 'datetime',
        'internership_complete' => 'datetime',
        'membership_expiration_date' => 'datetime',
        'marital_status' => MaritalStatus::class,
        'gender' => GenderEnum::class,
        'type' => DoctorType::class,
        'membership_status' => MembershipStatus::class,
        'visiting_date' => 'datetime',
        'registered_at' => 'datetime',
        'code' => 'string',
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
        return $this->belongsTo(AcademicDegree::class, 'academic_degree_id');
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

    public function doctor_rank()
    {
        return $this->belongsTo(DoctorRank::class);
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
        return $this->hasMany(Licence::class, 'licensable_id')->where('licensable_type', 'App\Models\Doctor')->orderBy('created_at', 'desc');
    }

    public function logs()
    {
        return $this->hasMany(Log::class, 'loggable_id')->where('loggable_type', 'App\Models\Doctor')->orderBy('created_at', 'desc');
    }

    public function countryGraduation()
    {
        return $this->belongsTo(Country::class, 'country_graduation_id');
    }


    public function getSpecializationAttribute()
    {
        return implode(' - ', array_filter([
            $this->specialty1?->name,
            $this->specialty2?->name,
        ]));
        
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class, 'init_doctor_id')->orderby('created_at', 'desc');
    }

    public function doctorRequests()
    {
        return $this->hasMany(DoctorRequest::class)->orderBy('created_at', 'desc');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'invoiceable_id')->where('invoiceable_type', 'App\Models\Doctor')->orderBy('created_at', 'desc');
    }


    public function getEcodeAttribute()
    {

        if($this->code)
        {
            return $this->branch->code . '-' . $this->code;
        } else {
            return null;
        }
    }


    public function institution()
    {
        return $this->belongsTo(Institution::class);
    }
}
