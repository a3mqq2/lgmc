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
        // ✅ Find or create Medical Facility Type
        $medicalFacilityType = !empty($row['nshat'])
            ? MedicalFacilityType::firstOrCreate(['name' => $row['nshat'], 'en_name' => $row['nshat']])
            : MedicalFacilityType::first();

        // ✅ Find doctor by name similarity
        $doctor = !empty($row['alasm'])
            ? Doctor::where('name', 'like', '%' . $row['alasm'] . '%')->first()
            : null;

        // ✅ Translate Arabic name to English
        $translatedName = $this->translateNameToEnglish($row['asm_alnshat']);
        // ✅ Create new Medical Facility with manager_id and en_name
        $MedicalFacility = MedicalFacility::create([
            'name' => $row['asm_alnshat'],
            'serial_number' => $row['rkm'],
            'commerical_number' => $row['sgl_tgaryrkyd'],
            'medical_facility_type_id' => $medicalFacilityType?->id,
            'manager_id' => $doctor?->id,
            "address" => "البيضاء",
            "branch_id" => 5,
            "activity_start_date" => $this->parseDate($row['t_altrkhys']),
            "phone_number" => $doctor->phone ?? '0921234567',
            "user_id" => auth()->id(),
        ]);


        // add licences 

        if(!empty($row['tgdyd_2022']))
        {
            $licence = new Licence();
            $licence->licensable_id = $MedicalFacility->id;
            $licence->licensable_type = \App\Models\MedicalFacility::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2022']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2022'], 1); // Expiry date +1 year
            $licence->status = "expired";
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();


            if(!$licence->issued_date && $row['tgdyd_2022'] == "ايقاف النشاط"){
                $MedicalFacility->membership_status = "inactive";
                $MedicalFacility->save();
            } else {
                if(!$licence->issued_date)
                {
                    $MedicalFacility->membership_status = "inactive";
                    $MedicalFacility->save();
                } else {
                    $licence->save();
                }
            }
        }



        if(!empty($row['tgdyd_2023']))
        {
            $licence = new Licence();
            $licence->licensable_id = $MedicalFacility->id;
            $licence->licensable_type = \App\Models\MedicalFacility::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2023']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2023'], 1); // Expiry date +1 year
            $licence->status = "expired";
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();

            if(!$licence->issued_date && $row['tgdyd_2023'] == "ايقاف النشاط"){
                $MedicalFacility->membership_status = "inactive";
                $MedicalFacility->save();
            } else {
                if(!$licence->issued_date)
                {
                    $MedicalFacility->membership_status = "inactive";
                    $MedicalFacility->save();
                } else {
                    $licence->save();
                }
            }
        }



        if(!empty($row['tgdyd_2024']))
        {
            $licence = new Licence();
            $licence->licensable_id = $MedicalFacility->id;
            $licence->licensable_type = \App\Models\MedicalFacility::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2024']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2024'], 1); // Expiry date +1 year

            if($licence->expiry_date > now()) {
                $licence->status = "active";
            } else {
                $licence->status = "expired";
            }

            $licence->branch_id = 5;
            $licence->created_by = auth()->id();

            if(!$licence->issued_date && $row['tgdyd_2024'] == "ايقاف النشاط"){
                $MedicalFacility->membership_status = "inactive";
                $MedicalFacility->save();
            } else {
                if(!$licence->issued_date)
                {
                    $MedicalFacility->membership_status = "inactive";
                    $MedicalFacility->save();
                } else {
                    $licence->save();
                }
            }

        }



        if(!empty($row['tgdyd_2025']))
        {
            $licence = new Licence();
            $licence->licensable_id = $MedicalFacility->id;
            $licence->licensable_type = \App\Models\MedicalFacility::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2025']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2025'], 1); // Expiry date +1 year
            $licence->status = "active";
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();


            if(!$licence->issued_date && $row['tgdyd_2025'] == "ايقاف النشاط"){
                $MedicalFacility->membership_status = "inactive";
                $MedicalFacility->save();
            } else {
                if(!$licence->issued_date)
                {
                    $MedicalFacility->membership_status = "inactive";
                    $MedicalFacility->save();
                } else {
                    $licence->save();
                }
            }

        }

    }

    /**
     * Translate Arabic names to English equivalents.
     */
    private function translateNameToEnglish($name)
    {
        $transliteration = [
            'ا' => 'a', 'ب' => 'b', 'ت' => 't', 'ث' => 'th', 'ج' => 'j',
            'ح' => 'h', 'خ' => 'kh', 'د' => 'd', 'ذ' => 'dh', 'ر' => 'r',
            'ز' => 'z', 'س' => 's', 'ش' => 'sh', 'ص' => 's', 'ض' => 'd',
            'ط' => 't', 'ظ' => 'z', 'ع' => 'a', 'غ' => 'gh', 'ف' => 'f',
            'ق' => 'q', 'ك' => 'k', 'ل' => 'l', 'م' => 'm', 'ن' => 'n',
            'ه' => 'h', 'و' => 'w', 'ي' => 'y', 'ء' => 'a', 'ى' => 'a',
            'ة' => 'h', 'ﻻ' => 'la', 'ﻷ' => 'la', 'ﻹ' => 'la', 'ﻵ' => 'la',
            ' ' => ' ', 'ٔ' => '', 'ً' => '', 'ٌ' => '', 'ٍ' => '', 'َ' => '',
            'ُ' => '', 'ِ' => '', 'ّ' => '', 'ْ' => '', 'ئ' => 'y', 'ؤ' => 'w'
        ];

        $translated = '';
        foreach (mb_str_split($name) as $char) {
            $translated .= $transliteration[$char] ?? $char;
        }

        // Capitalize the first letter of each word
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
    
            // ✅ Handle numeric (Excel serial date)
            if (is_numeric($date)) {
                $carbonDate = Carbon::instance(Date::excelToDateTimeObject($date));
            }
            // ✅ Handle 'YYYY/MM' or 'YYYY-MM' format
            elseif (preg_match('/^\d{4}[\/\-]\d{1,2}$/', $date)) {
                $carbonDate = Carbon::createFromFormat('Y/m', str_replace('-', '/', $date))
                    ?: Carbon::createFromFormat('Y-m', $date);
    
                // If no day is provided, default to the 1st of the month
                $carbonDate->day = 1;
            }
            // ✅ Handle 'DD/MM/YYYY' format
            elseif (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $date)) {
                $carbonDate = Carbon::createFromFormat('d/m/Y', $date);
            }
            // ✅ Attempt to parse with Carbon if no format matched
            else {
                $carbonDate = Carbon::parse($date);
            }
    
            // ✅ Add years if requested
            if ($add > 0) {
                $carbonDate->addYears($add);
            }
    
            return $carbonDate->format('Y-m-d');
        } catch (\Exception $e) {
            // Return null if any parsing fails
            return null;
        }
    }
    
}
