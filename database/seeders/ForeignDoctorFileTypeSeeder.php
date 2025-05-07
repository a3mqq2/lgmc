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
            'شهادة الامتياز الأصلية',
            'شهادة صحية من المختبر المرجعي'
            'صورة طبق الأصل للشهادة',
            'معادلة الشهادة',
            'حسن سيرة وسلوك من الدولة المتخرج منها الطبيب',
            'صورة من عقد العمل',
            'رسالة من جهة العمل',
            'جواز سفر ساري المفعول',
            'إقامة للعمل على أن يكون التوكيل الجهة الموقع معها العقد',
            'صورة من إذن مزاولة الطبيب',
        ];

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
