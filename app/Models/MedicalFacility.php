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
        return $this->hasMany(Doctor::class,'medical_facility_id');
    }

    public function medicalFacilityType() {
        return $this->belongsTo(MedicalFacilityType::class);
    }
    

    public function files() {
        return $this->hasMany(MedicalFacilityFile::class)->orderBy('order_number');
    }

    public function manager()
    {
        return $this->belongsTo(Doctor::class, 'manager_id');
    }

    public function licenses()
    {
        return $this->hasMany(Licence::class, 'medical_facility_id');
    }


    public function licenses_manager()
    {
        return $this->hasMany(Licence::class,'medical_facility_id');
    }



        /*---------------------------
        | Code generation
        ---------------------------*/
        protected static function booted(): void
        {
            // static::creating(function (MedicalFacility $facility) {
            //     $facility->setSequentialIndex();
            //     $facility->makeCode();
            // });
        }

        public function setSequentialIndex(): void
        {
            $this->index = self::where('branch_id', $this->branch_id)->max('index') + 1;
        }

        public function makeCode(): void
        {
            if($this->branch)
            {
                $this->loadMissing('branch');
                $this->code = $this->branch->code . '-MF-' . str_pad($this->index, 3, '0', STR_PAD_LEFT);
            } else {
                $this->code = 'MF-' . str_pad($this->index, 3, '0', STR_PAD_LEFT);
            }
        }

        public function regenerateCode(): void
        {
            $this->setSequentialIndex();
            $this->makeCode();
            $this->saveQuietly();
        }

        public static function regenerateAllCodes(): void
        {
            \DB::transaction(function () {
                Branch::with(['medicalFacilities' => fn($q) => $q->orderBy('id')])
                    ->chunkById(100, function ($branches) {
                        foreach ($branches as $branch) {
                            $i = 1;
                            foreach ($branch->medicalFacilities as $facility) {
                                $facility->index = $i;
                                $facility->code = $branch->code . '-MF-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                                $facility->saveQuietly();
                                $i++;
                            }
                        }
                    });
            });
        }

    
        public function getInvoicesAttribute()
        
        {
            return Invoice::where('doctor_id', $this->manager_id)
                ->whereIn('category', ['medical_facility_registration', 'medical_facility_renew'])
                ->get();
        }


        public function getVisitorDoctorsAttribute()
        {
            return Doctor::where('medical_facility_id', $this->id)
                ->where('type', 'visitor')
                ->get();
        }



        public function getVisitorDoctorsWithExpiredMembershipMissingReportFile()
        {
            $doctors = $this->doctors()
                ->where('type', 'visitor')
                ->where('membership_status', 'expired')
                ->whereDoesntHave('files', function ($query) {
                    $query->where('file_type_id', 54);
                })->get();

                return $doctors;
        }
        
}
