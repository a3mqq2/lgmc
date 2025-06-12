<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;
use App\Models\MedicalFacilityType;

class PrivateClinicFilesSeeder extends Seeder
{
    public function run(): void
    {
        $privateClinic = MedicalFacilityType::find(1); 

        if (!$privateClinic) {
            $this->command->error('لم يتم العثور على نوع المنشأة: Private Clinic');
            return;
        }

        $registrationFiles = [
            'شهاده سلبية',
            'وثيقة تأمين من هيئة التأمين الطبي',
        ];

        $index = 1;
        foreach ($registrationFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 1,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type' => 'single',
                'order_number' => $index++,
            ]);
        }

        // مستندات تجديد عيادة فردية
        $renewalFiles = [
            'شهادة سلبية سارية المفعول',
            'وثيقة تأمين من هيئة التأمين الطبي',
        ];

        $index = 1;
        foreach ($renewalFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 0,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type' => 'single',
                'order_number' => $index++,
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لعيادة فردية بنجاح.');
    }
}
