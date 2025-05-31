<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vault extends Model
{
    use HasFactory;

    protected $table = 'vaults'; // Specify the table name if different from the model name in plural form

    protected $primaryKey = 'id'; // Specify the primary key if it's different from the default 'id'

    protected $fillable = [
        'name', // Name of the vault
        'opening_balance', // Opening balance of the vault
        'branch_id', // ID of the branch to which the vault belongs
        'user_id'
    ];

    // Relationships

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id', 'id');
    }


    public function transactions() {
        return $this->hasMany(Transaction::class, 'vault_id');
    }
    // Add other relationships here as needed

    // Methods, scopes, or other customizations can be added here
}
