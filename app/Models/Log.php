<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Log extends Model
{
    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'action',
        'details',
        'user_id',
        'branch_id',
        'loggable_id',
        'loggable_type',
    ];

    /**
     * Get the user associated with the log.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the branch associated with the log.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get the parent loggable model (morph relationship).
     */
    public function loggable(): MorphTo
    {
        return $this->morphTo();
    }
}
