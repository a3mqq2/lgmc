<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function vault()
    {
        return $this->belongsTo(Vault::class);
    }

    public function medicalFacilities()
    {
        return $this->hasMany(MedicalFacility::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    public function manager()
    {
        return $this->belongsTo(Doctor::class, 'manager_id');
    }
}
