<?php

namespace App\Console\Commands;

use App\Models\Institution;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\MedicalFacility;

class ImportHospitalsToMedicalFacilities extends Command
{
    protected $signature = 'import:hospitals';
    protected $description = 'Import hospitals from old DB into medical_facilities table without type';

    public function handle()
    {
        $oldHospitals = DB::connection('lgmc_r')->table('hospitals')->get();

        foreach ($oldHospitals as $hospital) {
            Institution::create([
                'name' => $hospital->name,
                'address' => $hospital->address,
                'phone_number' => $hospital->contacts,
                'membership_status' => 'active',
                'activity_type' => 'commercial_record',
                'medical_facility_type_id' => 1,
                'branch_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->info("✅ Imported: {$hospital->name}");
        }

        $this->info('🎉 All hospitals have been successfully imported into medical_facilities.');
    }
}
