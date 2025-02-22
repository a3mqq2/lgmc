<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Licence;
use App\Models\Specialty;
use App\Models\Institution;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorsSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Map Arabic doctor ranks from Excel to their corresponding IDs.
     */
    protected $doctorRankMap = [
        'طبيب' => 1,        // Maps to 'طبيب ممارس'
        'اخصائي' => 3,      // Maps to 'أخصائي ثاني'
        'استشاري' => 5,     // Maps to 'استشاري'
    ];

    public function model(array $row)
    {
        if (empty($row['alasm'])) {
            return null; // Skip if name is missing
        }

        // Handle specialty
        $specialty = !empty($row['altkhss']) && $row['altkhss'] != "ممارس عام" 
            ? Specialty::firstOrCreate(['name' => $row['altkhss']]) 
            : null;

        // Handle institution
        $institution = null;
        if (!empty($row['gh_alaaml'])) {
            $institution = Institution::firstOrCreate(['name' => $row['gh_alaaml'], 'branch_id' => 5]);
        }





        $doctor = new Doctor([
            'doctor_number' => $row['aadoy'],
            'name' => $row['alasm'],
            'phone' => 0 . $row['rkm_alhatf'],
            'address' => $row['alakam'],
            'doctor_rank_id' => $this->doctorRankMap[$row['alsf']] ?? null,
            'specialty_1_id' => $specialty?->id ?? 1,
            'institution_id' => $institution?->id, // Assign institution_id if exists
            'certificate_of_excellence_date' =>  !empty($row['almohl']) && is_numeric(preg_replace('/\D/', '', $row['almohl'])) 
            ? preg_replace('/\D/', '', $row['almohl']) . '-01-01' 
            : null, 
            'date_of_birth' => !empty($row['almylad']) && is_numeric(preg_replace('/\D/', '', $row['almylad'])) 
            ? preg_replace('/\D/', '', $row['almylad']) . '-01-01' 
            : null,        
            'registered_at' => $this->parseDate($row['alantsab']),
            'branch_id' => 5,
            'code' => $row['aadoy'],
            'membership_status' => "active",
            'type' => "libyan",
            'notes' => $row['mlahthat'],
            'country_id' => 1,
        ]);

        // Save the doctor to get an ID
        $doctor->save();

        // Handle licences for specified years
        $years = ['tgdyd_2023', 'tgdyd_2024', 'tgdyd_2025', 'tgdyd_2026'];
        foreach ($years as $year) {
            if (!empty($row[$year])) {
                $issuedDate = $this->parseDate($row[$year]);
                $expiryDate = $this->parseDate($row[$year], 1); // Add 1 year to issue date

                // Skip if date parsing fails
                if (!$issuedDate || !$expiryDate) {
                    continue;
                }

                Licence::create([
                    'licensable_id' => $doctor->id,
                    'licensable_type' => Doctor::class,
                    'issued_date' => $issuedDate->format('Y-m-d'),
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'status' => $expiryDate->isPast() ? 'expired' : 'active',
                    'doctor_id' => $doctor->id,
                    'branch_id' => 5,
                    'created_by' => auth()->id(),
                    'doctor_type' => "libyan",
                ]);
            }
        }

        return $doctor; // Always return the model in ToModel imports
    }

    /**
     * Helper function to parse dates safely.
     */
   /**
 * Helper function to parse dates safely.
 */
private function parseDate($date, $add = 0)
{
    try {
        // Return null if date is empty, null, or not a valid string/number
        if (empty($date) || (!is_string($date) && !is_numeric($date))) {
            return null;
        }

        // Clean the date string: remove invalid characters and trim spaces
        $cleanedDate = trim(preg_replace('/[^0-9\-\/\.]/', '', $date));

        // Skip if cleaned date is empty or too short to be a valid date
        if (strlen($cleanedDate) < 4) {
            return null;
        }

        // Parse date based on type (Excel serial or string)
        $carbonDate = is_numeric($cleanedDate)
            ? Carbon::instance(Date::excelToDateTimeObject($cleanedDate))
            : Carbon::parse(str_replace(['/', '.'], '-', $cleanedDate));

        return $add > 0 ? $carbonDate->addYears($add) : $carbonDate;

    } catch (\Exception $e) {
        // Optional: Log the problematic date and reason for debugging
        \Log::warning('Date parsing failed', [
            'original_date' => $date,
            'cleaned_date' => $cleanedDate ?? null,
            'error' => $e->getMessage(),
        ]);

        return null; // Safely return null if parsing fails
    }
}

}
