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
        $medicalFacilityType = MedicalFacility::find(1);
        // 2. Find Doctor by name similarity
        $doctor = !empty($row[2])
            ? Doctor::where('name', 'like', '%' . $row[2] . '%')->first()
            : null;

        // 3. Create the Medical Facility
        

        
        if(isset($row[2]))
        {




            $MedicalFacility = MedicalFacility::create([
                'serial_number' => $row[0],
                'medical_facility_type_id' => 1,
                'manager_id' => $doctor?->id,
                'address' => $row[4] ?? 'طرابلس',
                'branch_id' => 1,
                'phone_number' => $row[5] ?? '0921234567',
                'user_id' => auth()->id(),
                'name' => $row[1],
            ]);
    
    
        }

    }


}
