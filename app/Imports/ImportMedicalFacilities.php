<?php

namespace App\Imports;

use App\Models\Doctor;
use App\Models\Licence;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityType;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ImportMedicalFacilities implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        // 1. Find or create the Medical Facility Type
        $medicalFacilityType = !empty($row['nshat'])
            ? MedicalFacilityType::firstOrCreate(['name' => $row['nshat'], 'en_name' => $row['nshat']])
            : MedicalFacilityType::first();

        // 2. Find Doctor by name similarity
        $doctor = !empty($row['alasm'])
            ? Doctor::where('name', 'like', '%' . $row['alasm'] . '%')->first()
            : null;

        // 3. Translate Arabic name to English (optional usage)
        $translatedName = $this->translateNameToEnglish($row['asm_alnshat']);

        // 4. Create the Medical Facility
        $MedicalFacility = MedicalFacility::create([
            'name' => $row['asm_alnshat'],
            'serial_number' => $row['rkm'],
            'commerical_number' => $row['sgl_tgaryrkyd'],
            'medical_facility_type_id' => $medicalFacilityType?->id,
            'manager_id' => $doctor?->id,
            'address' => 'البيضاء',
            'branch_id' => 5,
            'activity_start_date' => $this->parseDate($row['t_altrkhys']),
            'phone_number' => $doctor->phone ?? '0921234567',
            'user_id' => auth()->id(),
        ]);

        // 5. Check membership status when “إيقاف النشاط” is found
        //    We'll mark facility as inactive if no valid issued_date is parsed.
        //    This is handled inside createOrUpdateLicence().

        // 6. Create/Update Licences for each relevant year
        $yearsMap = [
            'tgdyd_2022',
            'tgdyd_2023',
            'tgdyd_2024',
            'tgdyd_2025'
        ];

        foreach ($yearsMap as $yearKey) {
            if (!empty($row[$yearKey])) {
                $this->createOrUpdateLicence($MedicalFacility, $row[$yearKey], $yearKey);
            }
        }

        return $MedicalFacility; // Return the model
    }

    /**
     * Create or update license record with proper status (active/expired/inactive).
     *
     * @param  \App\Models\MedicalFacility  $facility
     * @param  mixed  $value               The value from the Excel row (date or 'ايقاف النشاط')
     * @param  string $yearKey             The row key (e.g., tgdyd_2022)
     */
    private function createOrUpdateLicence(MedicalFacility $facility, $value, $yearKey)
    {
        // 1. Parse the issue date
        $issuedDate = $this->parseDate($value);
        // 2. Calculate expiry date (+1 year)
        $expiryDate = $issuedDate ? Carbon::parse($issuedDate)->addYear()->format('Y-m-d') : null;

        // 3. Create the Licence model
        $licence = new Licence();
        $licence->licensable_id = $facility->id;
        $licence->licensable_type = MedicalFacility::class;
        $licence->issued_date = $issuedDate;
        $licence->expiry_date = $expiryDate;
        $licence->branch_id = 5;
        $licence->created_by = auth()->id();

        /**
         * Determine Status:
         * - If 'ايقاف النشاط' and no valid issue date => membership & licence are inactive.
         * - If valid dates & expiry is in the future => 'active'.
         * - Else => 'expired'.
         */
        if (!$issuedDate && $value === 'ايقاف النشاط') {
            $facility->membership_status = 'inactive';
            $facility->save();
            // No licence saved because there's no valid date
            return;
        }

        // If no valid date but not specifically 'ايقاف النشاط'
        // => Mark facility as inactive, skip licence creation
        if (!$issuedDate) {
            $facility->membership_status = 'inactive';
            $facility->save();
            return;
        }

        // If we have a valid issued_date => set licence status active/expired
        if (Carbon::parse($expiryDate)->isFuture()) {
            $licence->status = 'active';
        } else {
            $licence->status = 'expired';
        }

        $licence->save();
    }

    /**
     * Translate Arabic names to English equivalents.
     */
    private function translateNameToEnglish($name)
    {
        $transliteration = [
            'ا' => 'a','ب' => 'b','ت' => 't','ث' => 'th','ج' => 'j','ح' => 'h','خ' => 'kh','د' => 'd','ذ' => 'dh',
            'ر' => 'r','ز' => 'z','س' => 's','ش' => 'sh','ص' => 's','ض' => 'd','ط' => 't','ظ' => 'z','ع' => 'a',
            'غ' => 'gh','ف' => 'f','ق' => 'q','ك' => 'k','ل' => 'l','م' => 'm','ن' => 'n','ه' => 'h','و' => 'w',
            'ي' => 'y','ء' => 'a','ى' => 'a','ة' => 'h','ﻻ' => 'la','ﻷ' => 'la','ﻹ' => 'la','ﻵ' => 'la','ٔ' => '',
            'ً' => '','ٌ' => '','ٍ' => '','َ' => '','ُ' => '','ِ' => '','ّ' => '','ْ' => '','ئ' => 'y','ؤ' => 'w'
        ];

        $translated = '';
        foreach (mb_str_split($name) as $char) {
            $translated .= $transliteration[$char] ?? $char;
        }
        return ucwords($translated);
    }

    /**
     * Helper function to parse dates safely.
     */
    private function parseDate($date, $add = 0)
    {
        try {
            if (empty($date)) {
                return null;
            }

            // ✅ Numeric => Excel serial date
            if (is_numeric($date)) {
                $carbonDate = Carbon::instance(Date::excelToDateTimeObject($date));
            }
            // ✅ 'YYYY/MM' or 'YYYY-MM'
            elseif (preg_match('/^\d{4}[\/\-]\d{1,2}$/', $date)) {
                // Convert to uniform 'YYYY-MM' format if needed
                $date = str_replace('/', '-', $date);
                $carbonDate = Carbon::createFromFormat('Y-m', $date);
                // Default day = 1
                $carbonDate->day = 1;
            }
            // ✅ 'DD/MM/YYYY'
            elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $date)) {
                $carbonDate = Carbon::createFromFormat('d/m/Y', $date);
            }
            // ✅ Otherwise parse freely
            else {
                $carbonDate = Carbon::parse($date);
            }

            if ($add > 0) {
                $carbonDate->addYears($add);
            }
            return $carbonDate->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // If parsing fails, return null
        }
    }
}
