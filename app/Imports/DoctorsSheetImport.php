<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Specialty;
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
        'أخصائي' => 3,      // Maps to 'أخصائي ثاني'
        'استشاري' => 5,     // Maps to 'استشاري'
    ];

    public function model(array $row)
    {


        if (empty($row['alasm'])) {
            return null; // Skip if name is missing
        }

        // Handle specialty
        $specialty = !empty($row['altkhss'])
            ? Specialty::firstOrCreate(['name' => $row['altkhss']])
            : Specialty::find(1); // Default specialty

        // Create Doctor instance (not saved yet)
        $doctor = new Doctor([
            'doctor_number' => $row['aadoy'],
            'name' => $row['alasm'],
            'phone' => $row['rkm_alhatf'],
            'address' => $row['alakam'],
            'doctor_rank_id' => $this->doctorRankMap[$row['alsf']] ?? null,
            'specialty_1_id' => $specialty?->id ?? 1,
            'certificate_of_excellence_date' => $this->parseDate($row['almohl']),
            'date_of_birth' => $this->parseDate($row['almohl']),
            'registered_at' => $this->parseDate($row['alantsab']),
            'branch_id' => 5,
            'code' => Doctor::max('id') + 1,
            'membership_status' => "active",
            'type' => "libyan",
            'notes' => $row['mlahthat'],
        ]);

        // ✅ Save doctor to get the ID
        $doctor->save();

        if (!empty($row['tsoy']) && is_numeric($row['tsoy'])) {
            // Now you can access $doctor->id
            Invoice::create([
                'invoiceable_id' => $doctor->id,
                'invoice_number' => 'SETT-' . $doctor->code,
                'amount' => $row['tsoy'],
                'invoiceable_type' => Doctor::class,
                'description' => "رسوم قيمة التسوية الباقية",
                'user_id' => auth()->id(),
                'status' => 'unpaid',
                'branch_id' => $doctor->branch_id,
            ]);
        }


        if (!empty($row['tgdyd_2023'])) {
       
        
            $licence = new \App\Models\Licence();
            $licence->licensable_id = $doctor->id;
            $licence->licensable_type = \App\Models\Doctor::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2023']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2023'], 1); // Expiry date +1 year
            $licence->status = "expired";
            $licence->doctor_id = $doctor->id;
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();
            $licence->doctor_type = "libyan";
        
            $licence->save();
        }



        if (!empty($row['tgdyd_2024'])) {
       
        
            $licence = new \App\Models\Licence();
            $licence->licensable_id = $doctor->id;
            $licence->licensable_type = \App\Models\Doctor::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2023']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2023'], 1); // Expiry date +1 year
            $licence->status = "expired";
            $licence->doctor_id = $doctor->id;
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();
            $licence->doctor_type = "libyan";
        
            $licence->save();
        }


        if (!empty($row['tgdyd_2025'])) {
       
        
            $licence = new \App\Models\Licence();
            $licence->licensable_id = $doctor->id;
            $licence->licensable_type = \App\Models\Doctor::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2023']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2023'], 1); // Expiry date +1 year
            $licence->status = "active";
            $licence->doctor_id = $doctor->id;
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();
            $licence->doctor_type = "libyan";
        
            $licence->save();
        }



        if (!empty($row['tgdyd_2026'])) {
       
        
            $licence = new \App\Models\Licence();
            $licence->licensable_id = $doctor->id;
            $licence->licensable_type = \App\Models\Doctor::class;
            $licence->issued_date = $this->parseDate($row['tgdyd_2023']); // Issue date from Excel
            $licence->expiry_date = $this->parseDate($row['tgdyd_2023'], 1); // Expiry date +1 year
            $licence->status = "active";
            $licence->doctor_id = $doctor->id;
            $licence->branch_id = 5;
            $licence->created_by = auth()->id();
            $licence->doctor_type = "libyan";
        
            $licence->save();
        }

        
        

        return $doctor; // Always return the model in ToModel imports
    }


    /**
     * Helper function to parse dates safely.
     */
    private function parseDate($date, $add = 0)
    {
        try {
            // If it's a numeric value, convert from Excel serial date to Carbon
            if (is_numeric($date)) {
                $carbonDate = Carbon::instance(Date::excelToDateTimeObject($date)); // Convert to Carbon
            } else {
                $carbonDate = Carbon::parse($date);
            }
    
            // ✅ If $add is provided, add the number of years
            if ($add > 0) {
                $carbonDate->addYears($add); // Correct Carbon method
            }
    
            return $carbonDate->format('Y-m-d');
        } catch (\Exception $e) {
            return null; // Return null if parsing fails
        }
    }
    
    
}
