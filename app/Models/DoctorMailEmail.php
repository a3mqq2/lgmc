<?php

// ===== App/Models/DoctorMailEmail.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMailEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_mail_id',
        'email_id',
        'email_value',
        'is_new',
        'unit_price'
    ];

    protected $casts = [
        'is_new' => 'boolean',
        'unit_price' => 'decimal:2'
    ];

    /**
     * Get the doctor mail that owns the email
     */
    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }

    /**
     * Get the email record (if exists in emails table)
     */
    public function email()
    {
        return $this->belongsTo(Email::class);
    }

    /**
     * Get the email display value
     */
    public function getDisplayEmailAttribute()
    {
        return $this->email_value;
    }

    /**
     * Check if this is a new email
     */
    public function getIsNewEmailAttribute()
    {
        return $this->is_new;
    }

    /**
     * Scope for new emails only
     */
    public function scopeNewEmails($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope for existing emails only
     */
    public function scopeExistingEmails($query)
    {
        return $query->where('is_new', false);
    }
}
