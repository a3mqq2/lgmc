<?php

// ===== App/Models/DoctorMail.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMail extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'contacted_before',
        'status',
        'grand_total',
        'notes',
        'work_mention',
        'edit_note',        // Add this
        'approved_at',      // Add this
        'approved_by',      // Add this
        'rejected_at',      // Add this
        'rejected_by',      // Add this
    ];

    protected $casts = [
        'contacted_before' => 'boolean',
        'grand_total' => 'decimal:2',
    ];

    // Status constants
    const STATUS_UNDER_APPROVE = 'under_approve';
    const STATUS_UNDER_EDIT = 'under_edit';
    const STATUS_UNDER_PAYMENT = 'under_payment';
    const STATUS_UNDER_PROCESS = 'under_proccess';
    const STATUS_DONE = 'done';
    const STATUS_CANCELED = 'canceled';

    // Work mention constants
    const WORK_MENTION_WITH = 'with';
    const WORK_MENTION_WITHOUT = 'without';

    /**
     * Get the doctor that owns the mail request
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the emails for the doctor mail
     */
    public function emails()
    {
        return $this->hasMany(DoctorMailEmail::class);
    }

    /**
     * Get the countries for the doctor mail
     */
    public function countries()
    {
        return $this->hasMany(DoctorMailCountry::class);
    }

    /**
     * Get the services for the doctor mail
     */
    public function services()
    {
        return $this->hasMany(DoctorMailService::class);
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            self::STATUS_UNDER_APPROVE => 'قيد الموافقة',
            self::STATUS_UNDER_EDIT => 'قيد التعديل',
            self::STATUS_UNDER_PAYMENT => 'قيد الدفع',
            self::STATUS_UNDER_PROCESS => 'قيد المعالجة',
            self::STATUS_DONE => 'مكتمل',
            self::STATUS_CANCELED => 'ملغى',
            default => 'غير محدد'
        };
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            self::STATUS_UNDER_APPROVE => 'bg-warning',
            self::STATUS_UNDER_EDIT => 'bg-info',
            self::STATUS_UNDER_PAYMENT => 'bg-primary',
            self::STATUS_UNDER_PROCESS => 'bg-secondary',
            self::STATUS_DONE => 'bg-success',
            self::STATUS_CANCELED => 'bg-danger',
            default => 'bg-light'
        };
    }

    /**
     * Get work mention label
     */
    public function getWorkMentionLabelAttribute()
    {
        return match($this->work_mention) {
            self::WORK_MENTION_WITH => 'مع ذكر جهة العمل',
            self::WORK_MENTION_WITHOUT => 'دون ذكر جهة العمل',
            default => 'غير محدد'
        };
    }

    /**
     * Calculate total emails amount
     */
    public function getTotalEmailsAmountAttribute()
    {
        return $this->emails->sum('unit_price');
    }

    /**
     * Calculate total services amount
     */
    public function getTotalServicesAmountAttribute()
    {
        return $this->services->sum('amount');
    }

    /**
     * Scope for specific status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for pending requests
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_UNDER_APPROVE);
    }

    /**
     * Scope for completed requests
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_DONE);
    }


    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
    
    public function rejectedBy()
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }
}