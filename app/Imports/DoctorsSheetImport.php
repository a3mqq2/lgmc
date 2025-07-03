<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Licence;
use App\Models\Specialty;
use App\Models\Institution;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DoctorsSheetImport implements ToModel, WithHeadingRow
{
    protected $licenceIndexes = [];

    protected $doctorRankMap = [
        'ممارس عام' => 1,
        'اخصائي' => 3,
        'طبيب ممارس' => 2,
        'استشاري' => 6,
    ];

    public function model(array $row)
    {
        if (empty($row['alasm'])) {
            return null;
        }

        $specialty = null;
        if (!empty($row['altkhss']) && $row['altkhss'] != "ممارس عام") {
            $specialty = Specialty::firstOrCreate(['name' => $row['altkhss'],'name_en' => '-']);
        }

        $institution = null;
        if (!empty($row['gh_alaaml'])) {
            $institution = Institution::firstOrCreate(['name' => $row['gh_alaaml'], 'branch_id' => 5]);
        }

        if (Doctor::where('name', $row['alasm'])->exists()) {
            return null;
        }

        if (empty($row['aadoy']) || !is_numeric($row['aadoy'])) {
            Log::warning('Doctor number is missing or invalid', ['row' => $row]);
            return null;
        }


        $doctor = new Doctor([
            'index' => $row['aadoy'],
            'name' => $row['alasm'],
            'phone' => 0 . $row['rkm_alhatf'],
            'address' => $row['alakam'],
            'doctor_rank_id' => $this->doctorRankMap[$row['alsf']] ?? null,
            'specialty_1_id' => $specialty?->id,
            'institution_id' => $institution?->id,
            'certificate_of_excellence_date' => !empty($row['almohl']) && is_numeric(preg_replace('/\D/', '', $row['almohl'])) 
                ? preg_replace('/\D/', '', $row['almohl']) . '-01-01' 
                : null,
            'registered_at' => $this->parseDate($row['alantsab']),
            'branch_id' => 3,
            'type' => "libyan",
            'country_id' => 1,
            'password' => bcrypt(123123123),
        ]);

        $doctor->save();

        if (!empty($row["tgdyd_2026"])) {
            $expiryDate = $this->parseDate($row["tgdyd_2026"]);
        
            if ($expiryDate && $expiryDate->format('m-d') === '12-31') {
                $issueDate = $expiryDate->copy()->startOfYear();
            } elseif ($expiryDate) {
                $issueDate = $expiryDate->copy()->addDay()->subYear();
            } else {
                $issueDate = now();
            }
        
            $year = $issueDate->format('Y');
            $branchCode = 'MIS';  
            $branchId =5;
            $type = Doctor::class;
        
            $key = "{$branchId}_{$type}_{$year}";
        
    
        
            $doctor->update([
                'membership_status' => $expiryDate ? $expiryDate->isPast() ? 'expired' : 'active' : 'under_approve',
                'membership_expiration_date' => $expiryDate ? $expiryDate->format('Y-m-d') : null,
            ]);

            $doctor->makeCode();
        }

        return $doctor;
    }

    private function parseDate($date)
    {
        try {
            if (empty($date) || (!is_string($date) && !is_numeric($date))) {
                return null;
            }

            $cleanedDate = trim(preg_replace('/[^0-9\-\/\.]/', '', $date));

            if (strlen($cleanedDate) < 4) {
                return null;
            }

            return is_numeric($cleanedDate)
                ? Carbon::instance(Date::excelToDateTimeObject($cleanedDate))
                : Carbon::parse(str_replace(['/', '.'], '-', $cleanedDate));

        } catch (\Exception $e) {
            Log::warning('Date parsing failed', [
                'original_date' => $date,
                'cleaned_date' => $cleanedDate ?? null,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }
}
