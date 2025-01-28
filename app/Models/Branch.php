<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    // Many-to-many relationship with Users
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    // One-to-one relationship with Vault
    public function vault()
    {
        return $this->belongsTo(Vault::class);
    }

    // One-to-many relationship with Medical Facilities
    public function medicalFacilities()
    {
        return $this->hasMany(MedicalFacility::class);
    }

    // One-to-many relationship with Invoices
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    // One-to-many relationship with Doctors
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }

    // One-to-many inverse relationship for the manager (Doctor)
    public function manager()
    {
        return $this->belongsTo(Doctor::class, 'manager_id');
    }

    // One-to-many relationship with Transactions (assuming Branch has many Transactions)
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    // One-to-many relationship with Licences
    public function licences()
    {
        return $this->hasMany(Licence::class);
    }

    // One-to-many relationship with Doctor Requests
    public function doctorRequests()
    {
        return $this->hasMany(DoctorRequest::class);
    }

    // One-to-many relationship with Tickets
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // One-to-many relationship with Branch-User (assuming it's a separate table)
    public function branchUsers()
    {
        return $this->hasMany(User::class);
    }
}
