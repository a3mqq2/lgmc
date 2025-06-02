<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;
use App\Models\MedicalFacilityType;

class PrivateClinicFilesSeeder extends Seeder
{
    public function run(): void
    {
        $privateClinic = MedicalFacilityType::find(1); // Assuming '2' is the ID for Medical Services

        if (!$privateClinic) {
            $this->command->error('لم يتم العثور على نوع المنشأة: Private Clinic');
            return;
        }

        // مستندات إنشاء عيادة فردية
        $registrationFiles = [
            'شهاده سلبية',
            'وثيقة تأمين من هيئة التأمين الطبي',
        ];

        foreach ($registrationFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 1,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type' => 'single'
            ]);
        }

        // مستندات تجديد عيادة فردية
        $renewalFiles = [
            'شهادة سلبية سارية المفعول',
            'وثيقة تأمين من هيئة التأمين الطبي',
        ];

        foreach ($renewalFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 0,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type' => 'single'
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لعيادة فردية بنجاح.');
    }
}
