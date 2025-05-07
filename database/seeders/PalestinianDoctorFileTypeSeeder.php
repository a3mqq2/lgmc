<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class PalestinianDoctorFileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'صورة شخصية',
            'إفادة التخرج',
            'كشف الدرجات',
            'شهادة الامتياز الأصلية',
            'صورة طبق الأصل للشهادة',
            'معادلة الشهادة',
            'حسن سيرة وسلوك من الدولة المتخرج منها الطبيب',
            'صورة من عقد العمل',
            'رسالة من جهة العمل',
            'جواز سفر ساري المفعول',
            'إقامة سارية المفعول فقط',
            'عقد إيجار',
            'صورة من إذن مزاولة الطبيب',
            'ورقة عمل من السفارة الفلسطينية - لإثبات عضوية في الاتحاد الفلسطيني',
        ];

        foreach ($items as $name) {
            FileType::create([
                'type' => 'doctor',
                'name' => $name,
                'is_required' => true,
                'doctor_type' => 'palestinian',
                'for_registration' => true,
                'facility_type' => null,
            ]);
        }
    }
}
