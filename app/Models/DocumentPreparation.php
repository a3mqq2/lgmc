<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

class DocumentPreparation extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_mail_id',
        'service_id',
        'document_type',
        'preparation_data',
        'status',
        'document_path',
        'prepared_at',
        'prepared_by',
        'notes'
    ];

    protected $casts = [
        'preparation_data' => 'array',
        'prepared_at' => 'datetime'
    ];

    // ============================================
    // العلاقات (Relationships)
    // ============================================

    public function doctorMail(): BelongsTo
    {
        return $this->belongsTo(DoctorMail::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(DoctorMailService::class, 'service_id');
    }

    public function preparedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prepared_by');
    }

    public function trainingRecords(): HasMany
    {
        return $this->hasMany(InternshipTrainingRecord::class);
    }

    public function gaps(): HasMany
    {
        return $this->hasMany(InternshipGap::class);
    }

    // ============================================
    // Accessors & Mutators
    // ============================================

    public function getDocumentTypeNameAttribute(): string
    {
        return match($this->document_type) {
            'good_standing' => 'شهادة حسن السيرة والسلوك',
            'specialist' => 'شهادة الاختصاص',
            'license' => 'رخصة الممارسة',
            'certificate' => 'الشهادة',
            'internship_second_year' => 'شهادة السنة الثانية للامتياز',
            default => $this->document_type
        };
    }

    public function getStatusNameAttribute(): string
    {
        return match($this->status) {
            'pending' => 'في الانتظار',
            'prepared' => 'تم الإعداد',
            'completed' => 'مكتمل',
            default => $this->status
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'prepared' => 'info',
            'completed' => 'success',
            default => 'secondary'
        };
    }

    public function getIsPreparedAttribute(): bool
    {
        return in_array($this->status, ['prepared', 'completed']);
    }

    public function getIsPendingAttribute(): bool
    {
        return $this->status === 'pending';
    }

    public function getIsCompletedAttribute(): bool
    {
        return $this->status === 'completed';
    }

    public function getTotalTrainingDaysAttribute(): int
    {
        return $this->trainingRecords->sum('duration_days') ?? 0;
    }

    public function getTotalGapDaysAttribute(): int
    {
        return $this->gaps->sum('duration_days') ?? 0;
    }

    public function getHasGapsAttribute(): bool
    {
        return $this->gaps()->exists();
    }

    public function getTrainingRecordsCountAttribute(): int
    {
        return $this->trainingRecords()->count();
    }

    // ============================================
    // Scopes
    // ============================================

    public function scopeForDocumentType($query, string $documentType)
    {
        return $query->where('document_type', $documentType);
    }

    public function scopePrepared($query)
    {
        return $query->whereIn('status', ['prepared', 'completed']);
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeForDoctorMail($query, int $doctorMailId)
    {
        return $query->where('doctor_mail_id', $doctorMailId);
    }

    public function scopeForService($query, int $serviceId)
    {
        return $query->where('service_id', $serviceId);
    }

    // ============================================
    // Methods
    // ============================================

    public function markAsPrepared(?string $documentPath = null, ?string $notes = null): void
    {
        $this->update([
            'status' => 'prepared',
            'document_path' => $documentPath,
            'prepared_at' => now(),
            'prepared_by' => auth()->id(),
            'notes' => $notes
        ]);
    }

    public function markAsCompleted(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function addTrainingRecord(array $data): InternshipTrainingRecord
    {
        return $this->trainingRecords()->create($data);
    }

    public function addGap(array $data): InternshipGap
    {
        return $this->gaps()->create($data);
    }

    public function calculateTotalDuration(): array
    {
        $trainingDays = $this->total_training_days;
        $gapDays = $this->total_gap_days;
        
        return [
            'training_days' => $trainingDays,
            'gap_days' => $gapDays,
            'net_training_days' => $trainingDays - $gapDays,
            'training_months' => round($trainingDays / 30, 1),
            'gap_months' => round($gapDays / 30, 1),
            'net_training_months' => round(($trainingDays - $gapDays) / 30, 1)
        ];
    }



}
