<?php
// app/Models/DoctorEmail.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'branch_id',
        'email',
        'country_id',
        'has_docs',     // Boolean: 0/1
        'last_year',    // آخر سنة استخراج (nullable)
    ];

    protected $casts = [
        'has_docs'  => 'boolean',
    ];

    /*── relations ─────────────────────────────────────────*/
    public function doctor()   { return $this->belongsTo(Doctor::class); }
    public function country()  { return $this->belongsTo(Country::class); }
    public function requests() { return $this->hasMany(DoctorEmailRequest::class); }
}
