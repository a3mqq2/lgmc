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

        // مستندات إنشاء عيادة فردية
        $registrationFiles = [
            [
                'name'         => 'شهاده سلبية',
                'slug'         => 'negative-certificate',
                'order_number' => 1,
            ],
            [
                'name'         => 'وثيقة تأمين من هيئة التأمين الطبي',
                'slug'         => 'medical-insurance-document',
                'order_number' => 2,
            ],
        ];

        foreach ($registrationFiles as $file) {
            FileType::create([
                'type'                     => 'medical_facility',
                'name'                     => $file['name'],
                'slug'                     => $file['slug'],
                'for_registration'         => 1,
                'is_required'              => 1,
                'doctor_type'              => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type'            => 'single',
                'order_number'             => $file['order_number'],
            ]);
        }

        // مستندات تجديد عيادة فردية
        $renewalFiles = [
            [
                'name'         => 'شهادة سلبية سارية المفعول',
                'slug'         => 'valid-negative-certificate',
                'order_number' => 1,
            ],
            [
                'name'         => 'وثيقة تأمين من هيئة التأمين الطبي',
                'slug'         => 'medical-insurance-document',
                'order_number' => 2,
            ],
        ];

        foreach ($renewalFiles as $file) {
            FileType::create([
                'type'                     => 'medical_facility',
                'name'                     => $file['name'],
                'slug'                     => $file['slug'],
                'for_registration'         => 0,
                'is_required'              => 1,
                'doctor_type'              => null,
                'medical_facility_type_id' => $privateClinic->id,
                'facility_type'            => 'single',
                'order_number'             => $file['order_number'],
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لعيادة فردية بنجاح.');
    }
}
