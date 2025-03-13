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
use Illuminate\Support\Facades\Log;

class DoctorsSheetImport implements ToModel, WithHeadingRow
{
    /**
     * Map Arabic doctor ranks from Excel to their corresponding IDs.
     */
    protected $doctorRankMap = [
        'طبيب' => 2,        // Maps to 'طبيب ممارس'
        'اخصائي' => 3,      // Maps to 'أخصائي ثاني'
        'استشاري' => 6,     // Maps to 'استشاري'
    ];

    public function model(array $row)
    {
        if (empty($row['alasm'])) {
            return null; // Skip if name is missing
        }

        // Handle specialty
        if (!empty($row['altkhss']) && $row['altkhss'] != "ممارس عام") {
            $specialty = Specialty::firstOrCreate(['name' => $row['altkhss']]);
        } else {
            $specialty = null;
        }

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
            'specialty_1_id' => $specialty?->id ?? null,
            'institution_id' => $institution?->id,
            'certificate_of_excellence_date' => !empty($row['almohl']) && is_numeric(preg_replace('/\D/', '', $row['almohl'])) 
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
            'country_id' => 1,
        ]);

        // Save the doctor to get an ID
        $doctor->save();

        // Handle licences using the issue date provided in the row
        // and calculating the expiry date as one year minus one day after the issue date.
        $years = ['tgdyd_2023', 'tgdyd_2024', 'tgdyd_2025', 'tgdyd_2026'];
        foreach ($years as $year) {
            if (!empty($row[$year])) {
                // Parse issue date from the row value
                $issueDate = $this->parseDate($row[$year]);
                // Calculate expiry date: one year added then subtract one day
                $expiryDate = $issueDate ? $issueDate->copy()->addYear()->subDay() : null;
        
                // Skip if date parsing fails
                if (!$issueDate || !$expiryDate) {
                    continue;
                }
        
                Licence::create([
                    'licensable_id' => $doctor->id,
                    'licensable_type' => Doctor::class,
                    'issued_date' => $issueDate->format('Y-m-d'),
                    'expiry_date' => $expiryDate->format('Y-m-d'),
                    'status' => $expiryDate->isPast() ? 'expired' : 'active',
                    'doctor_id' => $doctor->id,
                    'branch_id' => 5,
                    'created_by' => auth()->id(),
                    'doctor_type' => "libyan",
                ]);
            }
        }
        
        return $doctor;
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
