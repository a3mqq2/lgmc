<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\Specialty;

class ImportDoctorSpecialties extends Command
{
    protected $signature   = 'import:doctor-specialties';
    protected $description = 'Import doctor specialties and assign specialty_1_id from old database';

    public function handle(): void
    {
        $this->info('Starting specialty import...');

        $specialtyCache = Specialty::pluck('id', 'name')->toArray();

        DB::connection('lgmc_r')
            ->table('member_specialty')
            ->join('specialties', 'member_specialty.specialty_id', '=', 'specialties.id')
            ->select(
                'member_specialty.member_id',
                'specialties.name',
                'specialties.name_en'
            )
            ->orderBy('member_specialty.member_id')
            ->chunk(1000, function ($rows) use (&$specialtyCache) {

                $grouped = $rows->groupBy('member_id');

                foreach ($grouped as $memberId => $set) {
                    $nameAr = trim($set->pluck('name')->first());
                    $nameEn = trim($set->pluck('name_en')->first());

                    if ($nameAr === '') {
                        continue;
                    }

                    if (!isset($specialtyCache[$nameAr])) {
                        $spec = Specialty::create(['name' => $nameAr, 'name_en' => $nameEn]);
                        $specialtyCache[$nameAr] = $spec->id;
                    }

                    $doctor = Doctor::where('index', $memberId)->first();

                    if (!$doctor) {
                        continue;
                    }

                    $doctor->specialty_1_id = ($nameAr === 'ممارس عام') ? null : $specialtyCache[$nameAr];
                    $doctor->save();
                }
            });

        $this->info('Specialty import finished.');
    }
}
