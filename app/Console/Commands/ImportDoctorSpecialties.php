<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use App\Models\Doctor;
use App\Models\Specialty;

class ImportDoctorSpecialties extends Command
{
    protected $signature   = 'import:doctor-specialties';
    protected $description = 'Import doctor specialties and assign specialty_1_id from old database';

    public function handle(): void
    {
        $this->info('Fetching specialties from old DB ...');

        // preload existing specialties once (keyed by arabic name)
        $specialtyCache = Specialty::pluck('id', 'name')->toArray();

        // “ممارس عام” id (if exists) – used only for cache but shouldn’t be saved
        $gpId = $specialtyCache['ممارس عام'] ?? null;

        DB::connection('lgmc_r')
            ->table('member_specialty')
            ->join('specialties', 'member_specialty.specialty_id', '=', 'specialties.id')
            ->select('member_specialty.member_id', 'specialties.name', 'specialties.name_en')
            ->orderBy('member_specialty.member_id')
            ->chunkById(1000, function ($rows) use (&$specialtyCache, $gpId) {

                $grouped = $rows->groupBy('member_id');

                foreach ($grouped as $memberId => $set) {

                    $nameAr = trim($set->pluck('name')->first());
                    $nameEn = trim($set->pluck('name_en')->first());

                    // skip if name empty
                    if ($nameAr == '') { continue; }

                    // get or create specialty id (cache)
                    if (!isset($specialtyCache[$nameAr])) {
                        $new = Specialty::create(['name' => $nameAr, 'name_en' => $nameEn]);
                        $specialtyCache[$nameAr] = $new->id;
                    }
                    $specId = $specialtyCache[$nameAr];

                    // doctor
                    $doctor = Doctor::where('index', $memberId)->first();
                    if (!$doctor) { continue; }

                    // set / unset
                    $doctor->specialty_1_id = ($nameAr == 'ممارس عام') ? null : $specId;
                    $doctor->save();
                }
            });

        $this->info('Done importing specialties.');
    }
}
