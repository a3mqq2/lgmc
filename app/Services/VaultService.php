<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\FileType;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\University;
use App\Models\Transaction;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log as FacadesLog;

class VaultService
{
      public function createTransaction(array $data) 
      {
         $transaction = Transaction::create($data);
         return $transaction;
      }

      public function incrementBalance($vault,$amount) 
      {
         $vault->balance += $amount;
         $vault->save();
      }

      public function decrementBalance($vault,$amount)
      {
         $vault->balance -= $amount;
         $vault->save();
      }
      
}
