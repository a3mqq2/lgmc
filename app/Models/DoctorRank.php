<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorRank extends Model
{
    use HasFactory;
    protected $fillable = ['name','name_en'];
}
