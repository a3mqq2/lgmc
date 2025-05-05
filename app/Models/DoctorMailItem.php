<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorMailItem extends Model
{
    use HasFactory;

    protected $table = 'doctor_mail_items';

    protected $fillable = [
        'doctor_mail_id',
        'pricing_id',
        'status',
        'rejected_reason',
        'file',
    ];

    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }
}
