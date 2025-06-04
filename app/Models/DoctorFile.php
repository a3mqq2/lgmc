<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DoctorFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_id',
        'file_name',
        'file_path',
        'uploaded_at',
        "file_type_id",
    ];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function fileType() {
        return $this->belongsTo(FileType::class);
    }


}
