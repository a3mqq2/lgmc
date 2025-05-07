<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class VisitorDoctorFileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            [
                'name' => 'صورة شخصية',
                'for_registration' => true
            ],
            [
                'name' => 'رسالة من إدارة القطاع الخاص بوزارة الصحة موجهة للنقابة العامة للأطباء',
                'for_registration' => true
            ],
            [
                'name' => 'طلب كشف على ورقة شعار من جهة المعنية (شركات الخدمات الطبية)',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من ترخيص الشركة ساري المفعول',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من آلية الاتفاق بين الشركة والزائر والنسبة والكشف والعقد المبرم أو العلاقة المتقاضية',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من السيرة الذاتية للطبيب أو ما يوازيها من نقابة الزائر',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من شهادة التخرج والامتياز والشهادات العلمية بعد التخرج',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من جواز سفر الطبيب',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من وثيقة التأمين من هيئة التأمين الطبي',
                'for_registration' => true
            ],
            [
                'name' => 'صورة من التقرير بعد زيارة الطبيب الزائر',
                'for_registration' => false
            ],
        ];

        foreach ($items as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'is_required' => true,
                'doctor_type' => 'visitor',
                'for_registration' => $item['for_registration'],
                'facility_type' => null,
            ]);
        }
    }
}
