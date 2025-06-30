<?php

namespace App\Imports;

use App\Models\Doctor;
use App\Models\Institution;
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
        $doctor = !empty($row['r_alaadoy'])
            ? Doctor::where('index', $row['r_alaadoy'])->first()
            : null;

        // 3. Create the Medical Facility

        if(!$doctor)
        {
            dd($row['r_alaadoy']);
        }

        $MedicalFacility = MedicalFacility::create([
            'index' => $row['rkm'],
            'type' => 'private_clinic',
            'manager_id' => $doctor?->id,
            'branch_id' => 3,
            'address' =>'البيضاء',
            'phone_number' => '0',
            'name' => $row['asm_alnshat'],
            'commercial_number' => $row['sgl_tgaryrkyd'],
            'membership_expiration_date' => !empty($row['tgdyd_2026']) && is_numeric(preg_replace('/\D/', '', $row['tgdyd_2026']))
                ? preg_replace('/\D/', '', $row['tgdyd_2026']) . '-01-01'
                : null,
        ]);

        $MedicalFacility->update([
            'membership_status' => $MedicalFacility->membership_expiration_date->isPast() == 'active' ? 'active' : 'inactive',
        ]);

        $MedicalFacility->makeCode();



        $workPlaces = [
            // المراكز الطبية
            'مركز البيضاء الطبي',
            'مركز تشخيص وعلاج العقم',
            'مركز طيبة للتصوير الطبي',
            'مركز علاج السكري التعليمي',
            'مركز علاج الدرن والامراض الصدرية',
            'مركز الصحي ستلونه',
            'مركز وردامة الصحي',
            
            // المستشفيات التعليمية والعامة
            'مستشفى قورينا التعليمي',
            'مستشفى النساء والتوليد وطب الأطفال',
            'مستشفى المرج',
            'مستشفى الوحدة التعليمي',
            'مستشفى الوحدة درنة',
            'مستشفى سوسة العام',
            'المعهد القومي لعلاج الاورام',
            'مستشفى علاج الدرن والصدرية',
            
            // المستشفيات القروية
            'مستشفى مسة القروي',
            'مستشفى عمر المختار القروي',
            'مستشفى مراوة القروي',
            'مستشفى قندولة القروي',
            'مستشفى القيقب القروي',
            'مستشفى الحنية القروي',
            'مستشفى المخيلي',
            'مستشفى توكرة',
            'مستشفى القبة',
            'مستشفى قرنادة',
            'مستشفى عين مارة القروي',
            'مستشفى الابرق القروي',
            'مستشفى القبة القروي',
            'مستشفى العزيات القروي',
            
            // الجامعات
            'جامعة عمر المختار',
            'جامعة بنغازي',
            
            // الخدمات الصحية
            'الخدمات الصحية البيضاء',
            'الخدمات الصحية شحات',
            'الخدمات الصحية الساحل',
            'الخدمات الصحية درنة',
            'الخدمات الصحية طبرق',
            'الخدمات الصحية وردامة',
            'الخدمات الصحية القبة',
            'الخدمات الصحية جردس',
            'الخدمات الصحية الأبرق',
            'الخدمات الصحية القيقب',
            
            // عيادات متخصصة
            'عيادة السكر شحات',
            'عيادة جردس الجراري',
            
            // أخرى
            'الخبرة القضائية',
        ];

        foreach($workPlaces as $work_place)
        {
            Institution::create([
                'name' => $work_place,
                'branch_id' => 3,
            ]);
        }
    }


}
