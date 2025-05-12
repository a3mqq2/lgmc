<?php

namespace App\Models;

use App\Enums\DoctorType;
use App\Enums\GenderEnum;
use App\Enums\MaritalStatus;
use App\Enums\MembershipStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Doctor extends Authenticatable
{
    use HasFactory,Notifiable;

    protected $fillable = [
        'index',
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
        'graduation_date',
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
        'specialty_2',
        'medical_facility_id',
        'visit_from',
        'visit_to',
    ];



    // ملاحظات حول حقول الشهادة : 
    //  ١- internership_complete انتهاء الجامعه
    //  ٢- certificate_of_excellence_date مش معروف




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
        'email_verified_at'    => 'datetime',
        'documents_completed'  => 'boolean',
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

    public function institutions()
    {
        return $this->belongsToMany(Institution::class);
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
            $this->specialty_2,
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

    public function scopeByBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId)->orderBy('index');
    }

    /*---------------------------
    | Code generation
    ---------------------------*/
    protected static function booted(): void
    {
        static::creating(function (Doctor $doctor) {
            $doctor->setSequentialIndex();
            $doctor->makeCode();
        });
    }

    public function medicalFacility()
    {
        return $this->belongsTo(MedicalFacility::class);
    }


    public function medicalFacilities()
    {
        return $this->hasMany(MedicalFacility::class, 'manager_id')->where('branch_id', $this->branch_id);
    }

    public function setSequentialIndex(): void
    {
        $this->index = self::where('branch_id', $this->branch_id)->max('index') + 1;
    }

    public function makeCode(): void
    {
        if($this->branch) {
            $this->loadMissing('branch');
            $this->code = $this->branch->code . '-DR-' . str_pad($this->index, 3, '0', STR_PAD_LEFT);
        } else {
            $this->code = 'DR-' . str_pad($this->index, 3, '0', STR_PAD_LEFT);
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
        DB::transaction(function () {
            Branch::with(['doctors' => fn ($q) => $q->orderBy('id')])
                ->chunkById(100, function ($branches) {
                    foreach ($branches as $branch) {
                        $i = 1;
                        foreach ($branch->doctors as $doctor) {
                            $doctor->index = $i;
                            $doctor->code  = $branch->code . '-DR-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            $doctor->saveQuietly();
                            $i++;
                        }
                    }
                });
        });
    }

    public function doctor_mails()
    {
        return $this->hasMany(DoctorMail::class, 'doctor_id')
                    ->orderBy('created_at', 'desc');
    }


    public function getRankNameAttribute(): string
    {
        return $this->doctor_rank_id === 6
            ? 'استشاري تخصص دقيق'
            : $this->doctor_rank->name;
    }


    public function transfers()
    {
        return $this->hasMany(DoctorTransfer::class, 'doctor_id')
                    ->orderBy('created_at', 'desc');
    }

    public function getFullBreakAmountAttribute()
    {
        $invoices = Invoice::where('invoiceable_id', $this->id)->where('status', 'paid' )->where('invoiceable_type', Doctor::class)->sum('amount');
        $paid_amount = TotalInvoice::where('doctor_id', $this->id)->sum('total_amount');

        return $invoices - $paid_amount;
    }
}
