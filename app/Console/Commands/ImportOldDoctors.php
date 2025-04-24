<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\University;
use App\Models\Country;

class ImportOldDoctors extends Command
{
    protected $signature = 'import:doctors';
    protected $description = 'Import doctors from lgmc_r.members into LGMC.doctors';

    public function handle()
    {
        $oldDoctors = DB::connection('lgmc_r')->table('members')->get();

        foreach ($oldDoctors as $old) {
            try {
                // جلب الجامعة من قاعدة lgmc_r
                $oldUniversity = DB::connection('lgmc_r')->table('universities')
                    ->where('id', $old->qualification_university_id)
                    ->first();

                $newUniversityId = null;
                if ($oldUniversity) {
                    $newUniversity = University::where('name', $oldUniversity->name)
                        ->orWhere('en_name', $oldUniversity->name_en)
                        ->first();

                    if ($newUniversity) {
                        $newUniversityId = $newUniversity->id;
                    }
                }

                // جلب الدولة من قاعدة lgmc_r
                $oldCountry = DB::connection('lgmc_r')->table('countries')
                    ->where('id', $old->country_id)
                    ->first();

                $newCountryId = null;
                if ($oldCountry) {
                    $newCountry = Country::where('name', $oldCountry->country_arabic)
                        ->orWhere('en_name', $oldCountry->country_english)
                        ->first();

                    if ($newCountry) {
                        $newCountryId = $newCountry->id;
                    }
                }

                // تحديد النوع حسب الدولة
                $type = 'foreign';

                if ($newCountry) {
                    $countryName = strtolower(trim($newCountry->name));
                    $countryEnName = strtolower(trim($newCountry->en_name));
                
                    if ($countryName === 'ليبيا' || $countryEnName === 'libya') {
                        $type = 'libyan';
                    } elseif ($countryName === 'فلسطين' || $countryEnName === 'palestine') {
                        $type = 'palestinian';
                    }
                }
                
                $doctor = Doctor::create([
                    'doctor_number' => $old->id,
                    'name' => $old->name,
                    'name_en' => $old->name_en,
                    'national_number' => $old->national_id,
                    'mother_name' => $old->mother_name,
                    'date_of_birth' => $old->birthday,
                    'gender' => $old->gender == 1 ? 'male' : 'female',
                    'passport_number' => $old->pid,
                    'passport_expiration' => $old->pid_date,
                    'address' => $old->address,
                    'phone' => $old->phone,
                    'internership_complete' => $old->internership_complete,
                    'qualification_university_id' => $newUniversityId,
                    'certificate_of_excellence_date' => $old->qualification_date,
                    'doctor_rank_id' => $old->role,
                    'experience' => $old->experience,
                    'notes' => $old->note,
                    'registered_at' => $old->regist_date,
                    'created_at' => now(),
                    'updated_at' => now(),
                    'branch_id' => 1,
                    'country_id' => $newCountryId,
                    'type' => $type,
                ]);

                // توليد الكود
                $doctorCount = Doctor::where('branch_id', $doctor->branch_id)->count();
                $nextNumber = str_pad($doctorCount, 3, '0', STR_PAD_LEFT);
                $doctor->code = $nextNumber;
                $doctor->save();

                $this->info("✅ Imported: {$old->name}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to import ID {$old->id}: " . $e->getMessage());
            }
        }

        $this->info("🎉 All doctors imported successfully.");
    }
}
