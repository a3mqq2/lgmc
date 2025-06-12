<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class InternshipTrainingRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_preparation_id',
        'specialty',
        'institution',
        'from_date',
        'to_date',
        'duration_days',
        'notes'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date'
    ];

    // ============================================
    // العلاقات (Relationships)
    // ============================================

    public function documentPreparation(): BelongsTo
    {
        return $this->belongsTo(DocumentPreparation::class);
    }

    // ============================================
    // Accessors
    // ============================================

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_days) return '-';
        
        $months = intval($this->duration_days / 30);
        $days = $this->duration_days % 30;
        
        $result = '';
        if ($months > 0) $result .= $months . ' شهر ';
        if ($days > 0) $result .= $days . ' يوم';
        
        return trim($result) ?: '-';
    }

    public function getDurationInMonthsAttribute(): float
    {
        return round($this->duration_days / 30, 1);
    }

    public function getFormattedFromDateAttribute(): string
    {
        return $this->from_date ? $this->from_date->format('Y/m/d') : '-';
    }

    public function getFormattedToDateAttribute(): string
    {
        return $this->to_date ? $this->to_date->format('Y/m/d') : '-';
    }

    public function getDateRangeAttribute(): string
    {
        return $this->formatted_from_date . ' - ' . $this->formatted_to_date;
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeForSpecialty($query, string $specialty)
    {
        return $query->where('specialty', 'like', "%{$specialty}%");
    }

    public function scopeForInstitution($query, string $institution)
    {
        return $query->where('institution', 'like', "%{$institution}%");
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('from_date', [$startDate, $endDate])
              ->orWhereBetween('to_date', [$startDate, $endDate])
              ->orWhere(function($subQ) use ($startDate, $endDate) {
                  $subQ->where('from_date', '<=', $startDate)
                       ->where('to_date', '>=', $endDate);
              });
        });
    }

    // ============================================
    // Methods
    // ============================================

    public function calculateDuration(): void
    {
        if ($this->from_date && $this->to_date) {
            $this->duration_days = $this->from_date->diffInDays($this->to_date) + 1;
            $this->save();
        }
    }

    public function isOverlapping(InternshipTrainingRecord $other): bool
    {
        return $this->from_date <= $other->to_date && $this->to_date >= $other->from_date;
    }

    // ============================================
    // Events
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($record) {
            if ($record->from_date && $record->to_date) {
                $record->duration_days = $record->from_date->diffInDays($record->to_date) + 1;
            }
        });
    }
}

// ============================================
// Model 3: InternshipGap
// File: app/Models/InternshipGap.php
// Command: php artisan make:model InternshipGap
// ============================================

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class InternshipGap extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_preparation_id',
        'from_date',
        'to_date',
        'duration_days',
        'reason',
        'additional_notes'
    ];

    protected $casts = [
        'from_date' => 'date',
        'to_date' => 'date'
    ];

    // ============================================
    // العلاقات (Relationships)
    // ============================================

    public function documentPreparation(): BelongsTo
    {
        return $this->belongsTo(DocumentPreparation::class);
    }

    // ============================================
    // Accessors
    // ============================================

    public function getFormattedDurationAttribute(): string
    {
        if (!$this->duration_days) return '-';
        
        $months = intval($this->duration_days / 30);
        $days = $this->duration_days % 30;
        
        $result = '';
        if ($months > 0) $result .= $months . ' شهر ';
        if ($days > 0) $result .= $days . ' يوم';
        
        return trim($result) ?: '-';
    }

    public function getDurationInMonthsAttribute(): float
    {
        return round($this->duration_days / 30, 1);
    }

    public function getFormattedFromDateAttribute(): string
    {
        return $this->from_date ? $this->from_date->format('Y/m/d') : '-';
    }

    public function getFormattedToDateAttribute(): string
    {
        return $this->to_date ? $this->to_date->format('Y/m/d') : '-';
    }

    public function getDateRangeAttribute(): string
    {
        return $this->formatted_from_date . ' - ' . $this->formatted_to_date;
    }

    public function getShortReasonAttribute(): string
    {
        return substr($this->reason, 0, 50) . (strlen($this->reason) > 50 ? '...' : '');
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->where(function($q) use ($startDate, $endDate) {
            $q->whereBetween('from_date', [$startDate, $endDate])
              ->orWhereBetween('to_date', [$startDate, $endDate])
              ->orWhere(function($subQ) use ($startDate, $endDate) {
                  $subQ->where('from_date', '<=', $startDate)
                       ->where('to_date', '>=', $endDate);
              });
        });
    }

    public function scopeLongGaps($query, int $minimumDays = 30)
    {
        return $query->where('duration_days', '>=', $minimumDays);
    }

    // ============================================
    // Methods
    // ============================================

    public function calculateDuration(): void
    {
        if ($this->from_date && $this->to_date) {
            $this->duration_days = $this->from_date->diffInDays($this->to_date) + 1;
            $this->save();
        }
    }

    public function isLongGap(int $minimumDays = 30): bool
    {
        return $this->duration_days >= $minimumDays;
    }

    // ============================================
    // Events
    // ============================================

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($gap) {
            if ($gap->from_date && $gap->to_date) {
                $gap->duration_days = $gap->from_date->diffInDays($gap->to_date) + 1;
            }
        });
    }



    public function documentPreparations(): HasMany
    {
        return $this->hasMany(DocumentPreparation::class);
    }
    
    public function getHasPreparedDocumentsAttribute(): bool
    {
        return $this->documentPreparations()->prepared()->exists();
    }
    
    public function getDocumentPreparationForService($serviceId): ?DocumentPreparation
    {
        return $this->documentPreparations()
            ->where('service_id', $serviceId)
            ->first();
    }
    
    public function getPreparedDocumentsCountAttribute(): int
    {
        return $this->documentPreparations()->prepared()->count();
    }
    
    public function getCompletedDocumentsCountAttribute(): int
    {
        return $this->documentPreparations()->completed()->count();
    }
}
