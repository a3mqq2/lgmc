<?php

namespace App\Models;

use App\Enums\MembershipStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalFacility extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts =
    [
        "activity_start_date" => "date",
        "membership_expiration_date" => "date",
        "membership_status" => MembershipStatus::class,
    ];


    public function logs()
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

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

    public function medicalFacilityType() {
        return $this->belongsTo(MedicalFacilityType::class);
    }
    

    public function files() {
        return $this->hasMany(MedicalFacilityFile::class);
    }

    public function manager()
    {
        return $this->belongsTo(Doctor::class, 'manager_id');
    }

    public function licenses()
    {
        return $this->hasMany(Licence::class, 'licensable_id')->where('licensable_type', 'App\Models\MedicalFacility')->orderBy('created_at', 'desc');
    }


    public function licenses_manager()
    {
        return $this->hasMany(Licence::class,'medical_facility_id');
    }
}
