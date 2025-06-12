<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type'
    ];

    // Accessors
    public function getTypeNameAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'إيداع',
            'withdrawal' => 'سحب',
            default => $this->type
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'deposit' => 'success',
            'withdrawal' => 'danger',
            default => 'secondary'
        };
    }

    // Scopes
    public function scopeDeposits($query)
    {
        return $query->where('type', 'deposit');
    }

    public function scopeWithdrawals($query)
    {
        return $query->where('type', 'withdrawal');
    }
}
