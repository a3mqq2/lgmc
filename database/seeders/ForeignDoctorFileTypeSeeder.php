<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class ForeignDoctorFileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'صورة شخصية',
            'إفادة التخرج',
            'كشف الدرجات',
            'شهادة الامتياز',
            'شهادة صحية من المختبر المرجعي',
            'صورة طبق الأصل للشهادة',
            'صورة من عقد العمل',
            'رسالة من جهة العمل',
            'جواز سفر ساري المفعول',
            'اقامة بالعمل على ان يكون الكفيل الجهة الموقع معها العقد',
        ];

        $not_required = ['معادلة الشهادة (ان وجدت) ', 'صورة من إذن مزاولة  سابق للطبيب  (ان وجد)', 'شهائد تخصص (ان وجدت)'];

        foreach ($items as $name) {
            FileType::create([
                'type' => 'doctor',
                'name' => $name,
                'is_required' => true,
                'doctor_type' => 'foreign',
                'for_registration' => true,
                'facility_type' => null,
            ]);
        }
    }
}
