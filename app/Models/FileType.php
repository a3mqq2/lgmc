<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    use HasFactory;
    protected $fillable = ['type', 'name', 'is_required'];

    public function getFileTypeArAttribute()  {
        $ar_type = $this->type == "doctor" ? "طبيب" : "منشأه طبيه";
    }
}
