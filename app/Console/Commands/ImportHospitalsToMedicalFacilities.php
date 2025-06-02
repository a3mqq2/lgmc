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
                'branch_id' => 1,
            ]);

            $this->info("✅ Imported: {$hospital->name}");
        }

        $this->info('🎉 All hospitals have been successfully imported into medical_facilities.');
    }
}
