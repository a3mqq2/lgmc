<?php


// ===== App/Models/DoctorMailService.php =====
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class DoctorMailService extends Model
{
    use HasFactory;

    protected $fillable = [
        'doctor_mail_id',
        'pricing_id',
        'amount',
        'work_mention',
        'file_path',
        'file_name'
    ];

    protected $casts = [
        'amount' => 'decimal:2'
    ];

    // Work mention constants
    const WORK_MENTION_WITH = 'with';
    const WORK_MENTION_WITHOUT = 'without';

    /**
     * Get the doctor mail that owns the service
     */
    public function doctorMail()
    {
        return $this->belongsTo(DoctorMail::class);
    }

    /**
     * Get the pricing record
     */
    public function pricing()
    {
        return $this->belongsTo(Pricing::class);
    }

    /**
     * Get the service name from pricing
     */
    public function getServiceNameAttribute()
    {
        return $this->pricing ? $this->pricing->name : 'خدمة محذوفة';
    }

    /**
     * Get work mention label
     */
    public function getWorkMentionLabelAttribute()
    {
        return match($this->work_mention) {
            self::WORK_MENTION_WITH => 'مع ذكر جهة العمل',
            self::WORK_MENTION_WITHOUT => 'دون ذكر جهة العمل',
            default => 'غير محدد'
        };
    }

    /**
     * Check if service has file
     */
    public function getHasFileAttribute()
    {
        return !empty($this->file_path);
    }

    /**
     * Get file URL
     */
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

    /**
     * Get file download URL
     */
    public function getFileDownloadUrlAttribute()
    {
        return $this->file_path ? route('doctor-mail-service.download', $this->id) : null;
    }

    /**
     * Check if file exists
     */
    public function getFileExistsAttribute()
    {
        return $this->file_path && Storage::disk('public')->exists($this->file_path);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeAttribute()
    {
        if (!$this->file_path || !$this->file_exists) {
            return null;
        }

        $bytes = Storage::disk('public')->size($this->file_path);
        $units = ['B', 'KB', 'MB', 'GB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Scope for services with files
     */
    public function scopeWithFiles($query)
    {
        return $query->whereNotNull('file_path');
    }

    /**
     * Scope for services without files
     */
    public function scopeWithoutFiles($query)
    {
        return $query->whereNull('file_path');
    }

    /**
     * Scope for services with work mention
     */
    public function scopeWithWorkMention($query, $mention = null)
    {
        if ($mention) {
            return $query->where('work_mention', $mention);
        }
        return $query->whereNotNull('work_mention');
    }


    public function documentPreparation()
    {
        return $this->hasOne(DocumentPreparation::class, 'service_id');
    }
    
    public function getHasDocumentPreparationAttribute(): bool
    {
        return $this->documentPreparation()->exists();
    }
    
    public function getDocumentPreparationStatusAttribute(): ?string
    {
        return $this->documentPreparation?->status;
    }
    
    public function getDocumentPreparationStatusNameAttribute(): ?string
    {
        return $this->documentPreparation?->status_name;
    }
    
    public function getIsPreparedAttribute(): bool
    {
        return $this->documentPreparation?->is_prepared ?? false;
    }
}
