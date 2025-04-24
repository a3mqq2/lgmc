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
        // جلب التخصصات من قاعدة البيانات القديمة
        $oldSpecialties = DB::connection('lgmc_r')
            ->table('member_specialty')
            ->join('specialties', 'member_specialty.specialty_id', '=', 'specialties.id')
            ->select('member_specialty.member_id', 'specialties.name')
            ->get()
            ->groupBy('member_id');

        foreach ($oldSpecialties as $memberId => $specialties) {
            $firstSpecialtyName = $specialties->pluck('name')->unique()->first();

            if ($firstSpecialtyName) {
                // التأكد من وجود التخصص في الجدول الجديد أو إنشاؤه
                $specialty = Specialty::firstOrCreate(['name' => $firstSpecialtyName]);

                // ربط الطبيب بالتخصص
                $doctor = Doctor::where('doctor_number', $memberId)->first();

                if ($doctor) {
                    $doctor->specialty_1_id = $specialty->id;
                    $doctor->save();

                    $this->info("✅ Linked doctor {$doctor->id} to specialty '{$specialty->name}'");
                }
            }
        }

        $this->info('✅ specialty_1_id imported and linked successfully.');
    }
}
