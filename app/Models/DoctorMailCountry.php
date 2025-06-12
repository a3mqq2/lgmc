<?php


// ===== App/Models/DoctorMailCountry.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorMailCountry extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_mail_id',
        'country_id',
        'country_value',
        'is_new'
    ];

    protected $casts = [
        'is_new' => 'boolean'
    ];

    /**
     * Get the doctor mail that owns the country
     */
    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }

    /**
     * Get the country record (if exists in countries table)
     */
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    /**
     * Get the country display value
     */
    public function getDisplayCountryAttribute()
    {
        return $this->country ? $this->country->name_ar : $this->country_value;
    }

    /**
     * Check if this is a new country
     */
    public function getIsNewCountryAttribute()
    {
        return $this->is_new;
    }

    /**
     * Scope for new countries only
     */
    public function scopeNewCountries($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope for existing countries only
     */
    public function scopeExistingCountries($query)
    {
        return $query->where('is_new', false);
    }
}
