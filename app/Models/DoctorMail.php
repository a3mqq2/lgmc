<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorMail extends Model
{
    use HasFactory;

    protected $table = 'doctor_mails';

    protected $fillable = [
        'doctor_id',
        'contacted_before',
        'status',
        'notes',
        'countries',
        'emails',
        'grand_total',
    ];

    protected $casts = [
        'contacted_before' => 'boolean',
        'countries' => 'array',
        'emails' => 'array',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }


    public function doctorMailItems()
    {
        return $this->hasMany(DoctorMailItem::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

        /** Polymorphic invoice (if you prefer) */
        public function invoiceableInvoice()
        {
            return $this->morphOne(Invoice::class, 'invoiceable');
        }
    
}
