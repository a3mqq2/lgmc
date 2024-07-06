<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LicenceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'licence_id',
        'details',
        'user_id',
    ];

    public function licence()
    {
        return $this->belongsTo(Licence::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
