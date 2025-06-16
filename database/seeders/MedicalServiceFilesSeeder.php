<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;
use App\Models\MedicalFacilityType;

class MedicalServiceFilesSeeder extends Seeder
{
    public function run(): void
    {
        $medicalService = MedicalFacilityType::find(2);

        if (!$medicalService) {
            $this->command->error('لم يتم العثور على نوع المنشأة: Medical Services');
            return;
        }

        // مستندات إنشاء شركات خدمات طبية
        $registrationFiles = [
            [
                'name'         => 'سجل تجاري',
                'slug'         => 'commercial-register',
                'required'     => 1,
                'order_number' => 1,
            ],
            [
                'name'         => 'غرفة تجارية',
                'slug'         => 'chamber-of-commerce',
                'required'     => 1,
                'order_number' => 2,
            ],
            [
                'name'         => 'اذونات تآسيسيه نسخه طبق الاصل',
                'slug'         => 'foundation-permits-copy',
                'required'     => 1,
                'order_number' => 3,
            ],
            [
                'name'         => 'تعديلات ( ان تم التعديل )',
                'slug'         => 'modifications-if-any',
                'required'     => 0,
                'order_number' => 4,
            ],
            [
                'name'         => 'وثيقه تامين من هيئة التأمين الطبي',
                'slug'         => 'medical-insurance-document',
                'required'     => 1,
                'order_number' => 5,
            ],
        ];

        foreach ($registrationFiles as $file) {
            FileType::create([
                'type'                     => 'medical_facility',
                'name'                     => $file['name'],
                'slug'                     => $file['slug'],
                'for_registration'         => 1,
                'is_required'              => $file['required'],
                'doctor_type'              => null,
                'medical_facility_type_id' => $medicalService->id,
                'facility_type'            => 'services',
                'order_number'             => $file['order_number'],
            ]);
        }

        // مستندات تجديد شركات خدمات طبية
        $renewalFiles = [
            [
                'name'         => 'سجل تجاري ساري المفعول',
                'slug'         => 'valid-commercial-register',
                'required'     => 1,
                'order_number' => 1,
            ],
            [
                'name'         => 'غرفة تجارية سارية المفعول',
                'slug'         => 'valid-chamber-of-commerce',
                'required'     => 1,
                'order_number' => 2,
            ],
            [
                'name'         => 'تعديلات ( ان تم التعديل )',
                'slug'         => 'modifications-if-any',
                'required'     => 0,
                'order_number' => 3,
            ],
            [
                'name'         => 'وثيقة تأمين من هيئة التأمين الطبي',
                'slug'         => 'medical-insurance-document',
                'required'     => 1,
                'order_number' => 4,
            ],
        ];

        foreach ($renewalFiles as $file) {
            FileType::create([
                'type'                     => 'medical_facility',
                'name'                     => $file['name'],
                'slug'                     => $file['slug'],
                'for_registration'         => 0,
                'is_required'              => $file['required'],
                'doctor_type'              => null,
                'medical_facility_type_id' => $medicalService->id,
                'facility_type'            => 'services',
                'order_number'             => $file['order_number'],
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لخدمات طبية بنجاح.');
    }
}
