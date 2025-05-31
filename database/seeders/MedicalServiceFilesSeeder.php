<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;
use App\Models\MedicalFacilityType;

class MedicalServiceFilesSeeder extends Seeder
{
    public function run(): void
    {
        $medicalService = MedicalFacilityType::find(2); // Assuming '2' is the ID for Medical Services

        if (!$medicalService) {
            $this->command->error('لم يتم العثور على نوع المنشأة: Medical Services');
            return;
        }

        // مستندات إنشاء شركات خدمات طبية
        $registrationFiles = [
            ['name' => 'سجل تجاري', 'required' => 1],
            ['name' => 'غرفة تجارية', 'required' => 1],
            ['name' => 'اذن طبيب ساري المفعول', 'required' => 1],
            ['name' => 'صورة جواز السفر', 'required' => 1],
            ['name' => 'اذونات تآسيسيه نسخه طبق الاصل', 'required' => 1],
            ['name' => 'تعديلات ( ان تم التعديل )', 'required' => 0],
            ['name' => 'وثيقه تامين من هيئة التأمين الطبي', 'required' => 1],
        ];

        foreach ($registrationFiles as $file) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $file['name'],
                'for_registration' => 1,
                'is_required' => $file['required'],
                'doctor_type' => null,
                'medical_facility_type_id' => $medicalService->id,
                'facility_type' => 'services'
            ]);
        }

        // مستندات تجديد شركات خدمات طبية
        $renewalFiles = [
            ['name' => 'سجل تجاري ساري المفعول', 'required' => 1],
            ['name' => 'غرفة تجارية سارية المفعول', 'required' => 1],
            ['name' => 'اذن طبيب ساري المفعول', 'required' => 1],
            ['name' => 'جواز سفر ساري المفعول', 'required' => 1],
            ['name' => 'تعديلات ( ان تم التعديل )', 'required' => 0],
            ['name' => 'وثيقة تأمين من هيئة التأمين الطبي', 'required' => 1],
        ];

        foreach ($renewalFiles as $file) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $file['name'],
                'for_registration' => 0,
                'is_required' => $file['required'],
                'doctor_type' => null,
                'medical_facility_type_id' => $medicalService->id,
                'facility_type' => 'services',
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لخدمات طبية بنجاح.');
    }
}
