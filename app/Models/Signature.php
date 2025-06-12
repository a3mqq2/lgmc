<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Signature extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'name_en',
        'job_title_ar',
        'job_title_en',
        'branch_id',
        'is_selected',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_selected' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the branch that owns the signature.
     */
    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    /**
     * Get all of the signature's logs.
     */
    public function logs(): MorphMany
    {
        return $this->morphMany(Log::class, 'loggable');
    }

    /**
     * Scope a query to only include selected signatures.
     */
    public function scopeSelected($query)
    {
        return $query->where('is_selected', true);
    }

    /**
     * Scope a query to only include signatures for a specific branch.
     */
    public function scopeForBranch($query, $branchId)
    {
        return $query->where('branch_id', $branchId);
    }

    /**
     * Get the currently selected signature.
     */
    public static function getSelected()
    {
        return static::where('is_selected', true)->first();
    }

    /**
     * Set this signature as selected and unselect all others.
     */
    public function setAsSelected()
    {
        // Unselect all other signatures
        static::where('is_selected', true)->update(['is_selected' => false]);
        
        // Select this signature
        $this->update(['is_selected' => true]);
        
        return $this;
    }

    /**
     * Get the full name based on locale.
     */
    public function getFullNameAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->name : $this->name_en;
    }

    /**
     * Get the job title based on locale.
     */
    public function getJobTitleAttribute()
    {
        return app()->getLocale() === 'ar' ? $this->job_title_ar : $this->job_title_en;
    }

    /**
     * Get signature display name with job title.
     */
    public function getDisplayNameAttribute()
    {
        $name = $this->getFullNameAttribute();
        $jobTitle = $this->getJobTitleAttribute();
        
        return $jobTitle ? "{$name} - {$jobTitle}" : $name;
    }

    /**
     * Check if this signature is currently selected.
     */
    public function isSelected(): bool
    {
        return $this->is_selected;
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        // When creating a new signature, if it's marked as selected,
        // unselect all other signatures
        static::creating(function ($signature) {
            if ($signature->is_selected) {
                static::where('is_selected', true)->update(['is_selected' => false]);
            }
        });

        // When updating a signature, if it's being marked as selected,
        // unselect all other signatures
        static::updating(function ($signature) {
            if ($signature->is_selected && !$signature->getOriginal('is_selected')) {
                static::where('id', '!=', $signature->id)
                      ->where('is_selected', true)
                      ->update(['is_selected' => false]);
            }
        });
    }

    /**
     * Get signatures for API response.
     */
    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'name_en' => $this->name_en,
            'job_title_ar' => $this->job_title_ar,
            'job_title_en' => $this->job_title_en,
            'branch_id' => $this->branch_id,
            'branch' => $this->whenLoaded('branch'),
            'is_selected' => $this->is_selected,
            'full_name' => $this->getFullNameAttribute(),
            'job_title' => $this->getJobTitleAttribute(),
            'display_name' => $this->getDisplayNameAttribute(),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}