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
        'work_mention',
    ];

    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }

    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    // ✅ ترجمة لقيمة work_mention
    public function getWorkMentionLabelAttribute(): string
    {
        return match ($this->work_mention) {
            'with'    => 'مع ذكر جهة العمل',
            'without' => 'دون ذكر جهة العمل',
            default   => '',
        };
    }
}
