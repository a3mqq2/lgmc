<?php
// app/Models/DoctorEmailRequest.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorEmailRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_email_id',
        'pricing_id',
        'file_path',
        'status',      // pending | under_process | rejected | done
        'reason',
        'invoice_id',
    ];

    /*── relations ─────────────────────────────────────────*/
    public function email()   { return $this->belongsTo(DoctorEmail::class,'doctor_email_id'); }
    public function pricing() { return $this->belongsTo(Pricing::class); }
    public function invoice() { return $this->belongsTo(Invoice::class); }
}
