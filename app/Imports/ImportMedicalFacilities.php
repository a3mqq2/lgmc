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

        // 3. Create the Medical Facility
        $MedicalFacility = MedicalFacility::create([
            'name' => $row['asm_alnshat'],
            'serial_number' => $row['r_alaadoy'],
            'commerical_number' => $row['sgl_tgaryrkyd'],
            'medical_facility_type_id' => $medicalFacilityType?->id,
            'manager_id' => $doctor?->id,
            'address' => 'البيضاء',
            'branch_id' => 5,
            'phone_number' => $doctor->phone ?? '0921234567',
            'user_id' => auth()->id(),
        ]);

        // 4. Create/Update Licences for each relevant year
        $yearsMap = [
            'tgdyd_2022',
            'tgdyd_2023',
            'tgdyd_2024',
            'tgdyd_2025'
        ];

        foreach ($yearsMap as $yearKey) {
            if (!empty($row[$yearKey])) {
                // Parse issue date from the row value
                $issueDate = $this->parseDate($row[$yearKey]);
                // Calculate expiry date: one year added then subtract one day
                $expiryDate = $issueDate ? $issueDate->copy()->addYear()->subDay() : null;

                // Skip if date parsing fails
                if (!$issueDate || !$expiryDate) {
                    continue;
                }

                Licence::create([
                    'licensable_id' => $MedicalFacility->id,
                    'licensable_type' => MedicalFacility::class,
                    'issued_date' => $issueDate->format('Y-m-d'),
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'status' => $expiryDate->isPast() ? 'expired' : 'active',
                    'branch_id' => 5,
                    'created_by' => auth()->id(),
                ]);
            }
        }

        return $MedicalFacility;
    }

    /**
     * Create or update license record with proper status (active/expired/inactive).
     */
    private function createOrUpdateLicence(MedicalFacility $facility, $issueValue, $yearKey)
    {
        // 1. Parse the issue date
        $issueDate = $this->parseDate($issueValue);
        // 2. Calculate expiry date: add one year and subtract one day
        $expiryDate = $issueDate ? $issueDate->copy()->addYear()->subDay() : null;

        if (!$issueDate && $issueValue === 'ايقاف النشاط') {
            $facility->membership_status = 'inactive';
            $facility->save();
            return;
        }

        if (!$issueDate) {
            $facility->membership_status = 'inactive';
            $facility->save();
            return;
        }

        Licence::create([
            'licensable_id' => $facility->id,
            'licensable_type' => MedicalFacility::class,
            'issued_date' => $issueDate->format('Y-m-d'),
            'expiry_date' => $expiryDate->format('Y-m-d'),
            'status' => $expiryDate->isFuture() ? 'active' : 'expired',
            'branch_id' => 5,
            'created_by' => auth()->id(),
        ]);
    }

    /**
     * Helper function to parse dates safely.
     */
    private function parseDate($date)
    {
        try {
            if (empty($date) || (!is_string($date) && !is_numeric($date))) {
                return null;
            }

            // Clean the date string: remove invalid characters and trim spaces
            $cleanedDate = trim(preg_replace('/[^0-9\-\/\.]/', '', $date));

            if (strlen($cleanedDate) < 4) {
                return null;
            }

            // Parse date based on type (Excel serial or string)
            $carbonDate = is_numeric($cleanedDate)
                ? Carbon::instance(Date::excelToDateTimeObject($cleanedDate))
                : Carbon::parse(str_replace(['/', '.'], '-', $cleanedDate));

            return $carbonDate;
        } catch (\Exception $e) {
            \Log::warning('Date parsing failed', [
                'original_date' => $date,
                'cleaned_date' => $cleanedDate ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
