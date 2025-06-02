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
    use HasFactory, Notifiable;

    /* -----------------------------------------------------------------
     |  Global Attributes
     | -----------------------------------------------------------------*/
    protected $fillable = [
        'index', 'name', 'code', 'name_en', 'national_number', 'mother_name',
        'country_id', 'date_of_birth', 'marital_status', 'gender',
        'passport_number', 'passport_expiration', 'address', 'phone', 'phone_2',
        'email', 'hand_graduation_id', 'internership_complete', 'academic_degree_id',
        'qualification_university_id', 'certificate_of_excellence', 'graduation_date',
        'passport', 'id_card', 'employeer_message', 'id_number',
        'birthـcertificate', 'personal_photo', 'work_visa', 'health_certificate',
        'jobـcontract', 'anotherـcertificate', 'doctor_rank_id', 'ex_medical_facilities',
        'experience', 'notes', 'branch_id', 'specialty_1_id', 'specialty_2_id',
        'specialty_3_id', 'certificate_of_excellence_date', 'doctor_number',
        'country_graduation_id', 'type', 'due', 'membership_status',
        'membership_expiration_date', 'password', 'visiting_date', 'registered_at',
        'institution_id', 'specialty_2', 'medical_facility_id', 'visit_from',
        'academic_degree_univeristy_id',
        'visit_to', 'edit_note',
    ];

    protected $hidden = ['remember_token'];

    protected $casts = [
        'marital_status'             => MaritalStatus::class,
        'gender'                     => GenderEnum::class,
        'type'                       => DoctorType::class,
        'membership_status'          => MembershipStatus::class,
        'code'                       => 'string',
        'documents_completed'        => 'boolean',
    ];

    /* -----------------------------------------------------------------
     |  Relationships
     | -----------------------------------------------------------------*/
    public function country()             { return $this->belongsTo(Country::class); }
    public function handGraduation()      { return $this->belongsTo(University::class, 'hand_graduation_id'); }
    public function academicDegree()      { return $this->belongsTo(AcademicDegree::class, 'academic_degree_id'); }
    public function qualificationUniversity() { return $this->belongsTo(University::class, 'qualification_university_id'); }
    public function specialty1()          { return $this->belongsTo(Specialty::class, 'specialty_1_id'); }
    public function specialty2()          { return $this->belongsTo(Specialty::class, 'specialty_2_id'); }
    public function specialty3()          { return $this->belongsTo(Specialty::class, 'specialty_3_id'); }
    public function doctor_rank()         { return $this->belongsTo(DoctorRank::class); }
    public function doctorRank()         { return $this->belongsTo(DoctorRank::class); }
    public function branch()              { return $this->belongsTo(Branch::class); }
    public function institutions()        { return $this->belongsToMany(Institution::class); }
    public function files()               { return $this->hasMany(DoctorFile::class); }
    public function licenses()            { return $this->hasMany(Licence::class, 'doctor_id')->latest(); }
    public function logs()                { return $this->hasMany(Log::class, 'loggable_id')->where('loggable_type', self::class)->latest(); }
    public function countryGraduation()   { return $this->belongsTo(Country::class, 'country_graduation_id'); }
    public function tickets()             { return $this->hasMany(Ticket::class, 'init_doctor_id')->latest(); }
    public function doctorRequests()      { return $this->hasMany(DoctorRequest::class)->latest(); }
    public function invoices()            { return $this->hasMany(Invoice::class, 'doctor_id')->latest(); }
    public function institutionObj()         { return $this->belongsTo(Institution::class,'institution_id'); }
    public function medicalFacility()   { return $this->hasOne(MedicalFacility::class, 'manager_id')->where('branch_id', $this->branch_id); }
    public function doctor_mails()        { return $this->hasMany(DoctorMail::class)->latest(); }
    public function transfers()           { return $this->hasMany(DoctorTransfer::class)->latest(); }
    public function licence()             { return $this->hasOne(Licence::class)->latest(); }

    /* -----------------------------------------------------------------
     |  Accessors
     | -----------------------------------------------------------------*/
    public function getSpecializationAttribute(): string
    {
        return implode(' - ', array_filter([$this->specialty1?->name, $this->specialty_2]));
    }

    public function getEcodeAttribute(): ?string
    {
        return $this->code ?: null;
    }

    public function getFullBreakAmountAttribute(): float
    {
        $paid  = Invoice::where('invoiceable_id', $this->id)->where('status', 'paid')->where('invoiceable_type', self::class)->sum('amount');
        $total = TotalInvoice::where('doctor_id', $this->id)->sum('total_amount');
        return $paid - $total;
    }

    /* -----------------------------------------------------------------
     |  Scopes
     | -----------------------------------------------------------------*/
    public function scopeByBranch($query, int $branchId)
    {
        return $query->where('branch_id', $branchId)->orderBy('index');
    }

    /* -----------------------------------------------------------------
     |  Boot & Code Generation
     | -----------------------------------------------------------------*/
    protected static function booted(): void
    {
       
    }

    /**
     * Set sequential index based on doctor type rules.
     * Libyan: index per branch. Others: index per type globally.
     */
    public function setSequentialIndex(): void
    {
        if ($this->type === DoctorType::Libyan) {
            $this->index = self::where('branch_id', $this->branch_id)->max('index') + 1;
        } else {
            $this->index = self::where('type', $this->type)->max('index') + 1;
        }
    }

    /**
     * Generate code with type-specific prefix.
     * - Libyan     => {BRANCH}-LIB-###
     * - Visitor    => VIS-###
     * - Foreign    => FOR-###
     * - Palestinian=> PALES-###
     */
    public function makeCode(): void
    {
        $prefix = match ($this->type) {
            DoctorType::Libyan       => ($this->branch ? $this->branch->code . '-LIB-' : 'LIB-'),
            DoctorType::Visitor      => 'VIS-',
            DoctorType::Foreign      => 'FOR-',
            DoctorType::Palestinian  => 'PALES-',
        };

        $this->code = $prefix . str_pad($this->index, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Re-generate code & index for single doctor.
     */
    public function regenerateCode(): void
    {
        $this->setSequentialIndex();
        $this->makeCode();
        $this->saveQuietly();
    }

    public function academicDegreeUniversity()
    {
        return $this->belongsTo(University::class, 'academic_degree_univeristy_id');
    }

    /**
     * Bulk regeneration for all doctors (respects new rules).
     */
    public static function regenerateAllCodes(): void
    {
        DB::transaction(function () {

            // Libyan doctors -> sequential per branch
            Branch::with(['doctors' => fn ($q) => $q->where('type', DoctorType::Libyan)->orderBy('id')])
                ->chunkById(100, function ($branches) {
                    foreach ($branches as $branch) {
                        $i = 1;
                        foreach ($branch->doctors as $doc) {
                            $doc->index = $i;
                            $doc->code  = $branch->code . '-LIB-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            $doc->saveQuietly();
                            $i++;
                        }
                    }
                });

            // Non-Libyan doctors -> sequential per type
            collect([
                DoctorType::Visitor     => 'VIS-',
                DoctorType::Foreign     => 'FOR-',
                DoctorType::Palestinian => 'PALES-',
            ])->each(function ($prefix, $type) {
                $i = 1;
                self::where('type', $type)->orderBy('id')->chunkById(200, function ($docs) use (&$i, $prefix) {
                    foreach ($docs as $doc) {
                        $doc->index = $i;
                        $doc->code  = $prefix . str_pad($i, 3, '0', STR_PAD_LEFT);
                        $doc->saveQuietly();
                        $i++;
                    }
                });
            });
        });
    }
}
