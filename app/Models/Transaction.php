<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'desc', 'amount', 'branch_id', 'type','vault_id','balance','is_transfered',
        'financial_category_id','vault_transfer_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }



    public function vault() {
        return $this->belongsTo(Vault::class);
    }

    public function financialCategory() {
        return $this->belongsTo(FinancialCategory::class);
    }

    public function vaultTransfer() {
        return $this->belongsTo(Vault::class, 'vault_transfer_id');
    }
    
}
