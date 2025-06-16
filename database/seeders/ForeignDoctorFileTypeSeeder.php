<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class ForeignDoctorFileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $requiredItems = [
            ['name' => 'صورة شخصية', 'slug' => 'personal_image'],
            ['name' => 'إفادة التخرج', 'slug' => 'graduation_certificate'],
            ['name' => 'كشف الدرجات', 'slug' => 'graduation_statement'], 
            ['name' => 'شهادة الامتياز', 'slug' => 'internship_certificate'],
            ['name' => 'شهادة صحية من المختبر المرجعي', 'slug' => 'health_certificate'],
            ['name' => 'صورة طبق الأصل للشهادة', 'slug' => 'certified_copy_certificate'],
            ['name' => 'صورة من عقد العمل', 'slug' => 'employment_contract'],
            ['name' => 'رسالة من جهة العمل', 'slug' => 'employment_letter'],
            ['name' => 'جواز سفر ساري المفعول', 'slug' => 'passport'],
            ['name' => 'اقامة بالعمل على ان يكون الكفيل الجهة الموقع معها العقد', 'slug' => 'work_residence'],
        ];

        $notRequiredItems = [
            ['name' => 'معادلة الشهادة (ان وجدت)', 'slug' => 'certificate_equivalency'],
            ['name' => 'صورة من إذن مزاولة سابق للطبيب (ان وجد)', 'slug' => 'previous_practice_license'],
            ['name' => 'شهائد تخصص (ان وجدت)', 'slug' => 'specialization_certificates'],
        ];

        $orderNumber = 1;

        // إضافة الملفات المطلوبة
        foreach ($requiredItems as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'slug' => $item['slug'],
                'is_required' => true,
                'doctor_type' => 'foreign',
                'for_registration' => true,
                'facility_type' => null,
                'order_number' => $orderNumber++,
            ]);
        }

        // إضافة الملفات غير المطلوبة
        foreach ($notRequiredItems as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'slug' => $item['slug'],
                'is_required' => false,
                'doctor_type' => 'foreign',
                'for_registration' => true,
                'facility_type' => null,
                'order_number' => $orderNumber++,
            ]);
        }
    }
}