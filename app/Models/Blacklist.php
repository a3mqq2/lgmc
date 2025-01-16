<?php

namespace App\Models;

use App\Enums\DoctorType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blacklist extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blacklists';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'number_phone',
        'passport_number',
        'id_number',
        'doctor_type',
        'reason',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'doctor_type' => 'string',
        'passport_number' => 'string',
        'id_number' => 'string',
        'number_phone' => 'string',
        'name' => 'string',
        'doctor_type' => DoctorType::class,
    ];

    /**
     * Validation rules for the model.
     *
     * You can use these rules in your controllers or request classes.
     *
     * @var array<string, mixed>
     */
    public static $rules = [
        'name' => 'required|string|max:255',
        'number_phone' => 'required|string|max:20',
        'passport_number' => 'nullable|string|max:50',
        'id_number' => 'nullable|string|max:50',
        'doctor_type' => 'nullable|in:foreign,visitor,libyan,palestinian',
    ];

    /**
     * Scope a query to only include a specific doctor type.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfDoctorType($query, $type)
    {
        return $query->where('doctor_type', $type);
    }

    /**
     * Accessor to get the full identifier.
     *
     * @return string
     */
    public function getFullIdentifierAttribute()
    {
        return "{$this->name} - {$this->number_phone}";
    }
}
