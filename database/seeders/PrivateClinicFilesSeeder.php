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
            'صورة شخصية',
            'شهاده سلبية',
            'جواز سفر ساري المفعول',
            'وثيقة تأمين من هيئة التأمين الطبي',
            'اذن الطبيب ( اخصائي او استشاري )',
        ];

        foreach ($registrationFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 1,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
            ]);
        }

        // مستندات تجديد عيادة فردية
        $renewalFiles = [
            'صورة شخصية',
            'شهادة سلبية سارية المفعول',
            'جواز السفر',
            'وثيقة تأمين',
            'اذن ساري للطبيب',
        ];

        foreach ($renewalFiles as $name) {
            FileType::create([
                'type' => 'medical_facility',
                'name' => $name,
                'for_registration' => 0,
                'is_required' => 1,
                'doctor_type' => null,
                'medical_facility_type_id' => $privateClinic->id,
            ]);
        }

        $this->command->info('تمت إضافة مستندات التسجيل والتجديد لعيادة فردية بنجاح.');
    }
}
