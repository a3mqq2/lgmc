<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Doctor;
use App\Models\University;
use App\Models\Country;
use App\Models\Institution;
use App\Models\Specialty;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ImportOldDoctors extends Command
{
    protected $signature = 'import:doctors';
    protected $description = 'Import doctors from lgmc_r.members into LGMC.doctors with institutions, specialties, and finances';

    public function handle()
    {
        $this->info('Starting doctors import...');

        $oldDoctors    = DB::connection('lgmc_r')->table('members')->get();
        $importedCount = 0;
        $failedCount   = 0;

        foreach ($oldDoctors as $old) {
            try {
                $quniversityId = $this->mapUniversity($old->qualification_university_id);
                $universityId = $this->mapUniversity($old->university_id);
                $countryId    = $this->mapCountry($old->country_id);
                $type         = $this->determineDoctorType($countryId);

                $roleToRank = [
                    1 => 2, // طبيب ممارس
                    2 => 3, // أخصائي
                    3 => 6, // استشاري
                    4 => 1, // ممارس عام
                ];

                $institutionId = $this->getFirstInstitution($old->id);
                $specialtyId   = $this->getFirstSpecialty($old->id);

                [$expirationDate, $status] = $this->getFinanceData($old->id);

                $doctor = Doctor::create([
                    'doctor_number'               => $old->id,
                    'name'                        => $old->name,
                    'name_en'                     => $old->name_en,
                    'national_number'             => $old->national_id,
                    'mother_name'                 => $old->mother_name_en,
                    'date_of_birth'               => $old->birthday,
                    'gender'                      => $old->gender == 1 ? 'male' : 'female',
                    'passport_number'             => $old->pid,
                    'passport_expiration'         => $old->pid_date,
                    'address'                     => $old->address,
                    'phone'                       => $old->phone,
                    'internership_complete'       => $old->internership_complete,
                    'qualification_university_id' => $quniversityId,
                    'hand_graduation_id'          => $universityId,
                    'certificate_of_excellence_date' => $old->qualification_date,
                    'doctor_rank_id'              => $roleToRank[$old->role] ?? null,
                    'experience'                  => $old->experience,
                    'notes'                       => $old->note,
                    'registered_at'               => $old->regist_date,
                    'created_at'                  => now(),
                    'updated_at'                  => now(),
                    'branch_id'                   => 1,
                    'country_id'                  => $countryId,
                    'type'                        => $type,
                    'index'                       => $old->id,
                    'membership_status'           => $status,
                    'membership_expiration_date'  => $expirationDate,
                    'password'                    => Hash::make($old->phone),
                    'email_verified_at'           => now(),
                    'documents_completed'         => true,
                    'institution_id'              => $institutionId,
                    'specialty_1_id'              => isset($roleToRank[$old->role]) && $roleToRank[$old->role] == 1 ? null : $specialtyId,
                ]);

                $doctor->makeCode();
                $doctor->save();

                $importedCount++;
                $this->info("✓ Imported: {$old->name} (ID: {$old->id})");

            } catch (\Exception $e) {
                $failedCount++;
                $this->error("✗ Failed to import ID {$old->id} ({$old->name}): " . $e->getMessage());
            }
        }

        $this->info("\n" . str_repeat('=', 50));
        $this->info("✓ Successfully imported: {$importedCount} doctors");
        if ($failedCount > 0) {
            $this->error("✗ Failed to import: {$failedCount} doctors");
        }
        $this->info(str_repeat('=', 50));
    }

    private function mapUniversity($oldUniversityId)
    {
        if (!$oldUniversityId) return null;

        $old = DB::connection('lgmc_r')->table('universities')->where('id', $oldUniversityId)->first();
        if (!$old) return null;

        return optional(
            University::where('name', $old->name)->orWhere('en_name', $old->name_en)->first()
        )->id;
    }

    private function mapCountry($oldCountryId)
    {
        if (!$oldCountryId) return null;

        $old = DB::connection('lgmc_r')->table('countries')->where('id', $oldCountryId)->first();
        if (!$old) return null;

        return optional(
            Country::where('country_name_ar', $old->country_arabic)->orWhere('country_name_en', $old->country_english)->first()
        )->id;
    }

    private function determineDoctorType($countryId)
    {
        if (!$countryId) return 'foreign';

        $country = Country::find($countryId);
        if (!$country) return 'foreign';

        $ar = strtolower(trim($country->country_name_ar));
        $en = strtolower(trim($country->country_name_en));

        return $ar === 'ليبيا' || $en === 'libya'
            ? 'libyan'
            : ($ar === 'فلسطين' || $en === 'palestine' ? 'palestinian' : 'foreign');
    }

    private function getFirstInstitution($memberId)
    {
        $record = DB::connection('lgmc_r')->table('hospital_member')
            ->where('member_id', $memberId)->first();

        return optional($record)->hospital_id
            ? optional(Institution::find($record->hospital_id))->id
            : null;
    }

    private function getFirstSpecialty($memberId)
    {
        $record = DB::connection('lgmc_r')->table('member_specialty')
            ->where('member_id', $memberId)->first();

        return $record ? $this->mapSpecialty($record->specialty_id) : null;
    }

    private function mapSpecialty($oldSpecialtyId)
    {
        if (!$oldSpecialtyId) return null;

        $old = DB::connection('lgmc_r')->table('specialties')->where('id', $oldSpecialtyId)->first();
        if (!$old) return null;

        $new = Specialty::where('name', $old->name)
            ->orWhere('name_en', $old->name_en ?? $old->name)->first()
            ?? Specialty::find($oldSpecialtyId);

        return $new
            ? $new->id
            : Specialty::create(['name' => $old->name, 'name_en' => $old->name_en ?? $old->name])->id;
    }

    /**
     * Fetch latest إشتراك سنوي finance record and compute expiration date & status.
     */
    private function getFinanceData($memberId): array
    {
        $finance = DB::connection('lgmc_r')->table('finances')
            ->where('member_id', $memberId)
            ->where('name', 'LIKE', '%اشتراك سنوي%')
            ->orderByDesc('year')
            ->first();


        if (!$finance) {
            return [null, 'expired'];
        }

        $expiration = Carbon::parse($finance->year)->addYear();
        $status     = $expiration->lt(Carbon::today()) ? 'expired' : 'active';

        return [$expiration->format('Y-m-d'), $status];
    }
}
