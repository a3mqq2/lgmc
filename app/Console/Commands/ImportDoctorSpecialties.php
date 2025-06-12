<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\Specialty;

class ImportDoctorSpecialties extends Command
{
    protected $signature = 'import:doctor-specialties';
    protected $description = 'Import doctor specialties and assign specialty_1_id from old database';

    public function handle()
    {
        $oldSpecialties = DB::connection('lgmc_r')
            ->table('member_specialty')
            ->join('specialties', 'member_specialty.specialty_id', '=', 'specialties.id')
            ->select('member_specialty.member_id', 'specialties.name')
            ->get()
            ->groupBy('member_id');

        foreach ($oldSpecialties as $memberId => $specialties) {
            $firstSpecialtyName = $specialties->pluck('name')->unique()->first();
            $firstNameEn = $specialties->pluck('name_en')->unique()->first();
            if ($firstSpecialtyName) {
                $specialty = Specialty::firstOrCreate(['name' => $firstSpecialtyName,'name_en' => $firstNameEn]);

                $doctor = Doctor::where('doctor_number', $memberId)->first();

                if ($doctor) {
                    if ($specialty->name === 'ممارس عام') {
                        $doctor->specialty_1_id = null;
                    } else {
                        $doctor->specialty_1_id = $specialty->id;
                    }

                    $doctor->save();

                    $this->info("✅ Linked doctor {$doctor->id} to specialty '{$specialty->name}'");
                }
            }
        }

        $this->info('✅ specialty_1_id imported and linked successfully.');
    }
}
