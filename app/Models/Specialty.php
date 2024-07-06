<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'specialty_id',
    ];


    public function specialities() {
        return $this->hasMany(Specialty::class,'specialty_id');
    }
    public function subSpecialty()
    {
        return $this->belongsTo(Specialty::class, 'specialty_id');
    }
}
