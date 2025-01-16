<?php

namespace App\Models;

use App\Enums\DoctorType;
use App\Enums\RequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRequest extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'date',
        'pricing_id',
        'notes',
        'status',
        'branch_id',
        'rejected_by',
        'rejected_at',
        'approved_by',
        'approved_at',
        'done_by',
        'done_at',
        'canceled_at',
        'doctor_type',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'rejected_at' => 'datetime',
        'approved_at' => 'datetime',
        'done_at' => 'datetime',
        'canceled_at' => 'datetime',
        "status" => RequestStatus::class,
        'doctor_type' =>  DoctorType::class,
    ];

    /**
     * Status constants.
     */
   


    /**
     * Get all available statuses.
     *
     * @return array
     */
  

    /**
     * Relationship: DoctorRequest belongs to a Doctor.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Relationship: DoctorRequest belongs to a Pricing.
     */
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    /**
     * Relationship: DoctorRequest belongs to a Branch.
     */
    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Relationship: DoctorRequest was rejected by a User.
     */
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    /**
     * Relationship: DoctorRequest was approved by a User.
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Relationship: DoctorRequest was completed by a User.
     */
    public function doneBy()
    {
        return $this->belongsTo(User::class, 'done_by');
    }

    // If you add a 'canceled_by' field in the future, you can uncomment the following method:
    /*
    /**
     * Relationship: DoctorRequest was canceled by a User.
     */
    /*
    public function canceledBy()
    {
        return $this->belongsTo(User::class, 'canceled_by');
    }
    */


    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
