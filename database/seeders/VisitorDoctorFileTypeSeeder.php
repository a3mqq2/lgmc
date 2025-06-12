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
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 1
            ],
            [
                'name' => 'رسالة من إدارة القطاع الخاص بوزارة الصحة موجهة للنقابة العامة للأطباء',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 2
            ],
            [
                'name' => 'طلب   ورقة شعار من جهة المعنية (شركات الخدمات الطبية)',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 3
            ],
            [
                'name' => 'صورة من آلية الاتفاق بين الشركة والزائر والنسبة والكشف والعقد المبرم أو العلاقة المتقاضية',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 5
            ],
            [
                'name' => 'صورة من حسن السيرة والسلوك عبر البريد الالكتروني من نقابة البلد الى ليبيا',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 6
            ],
            [
                'name' => 'صورة من شهادة التخرج والامتياز والشهادات العلمية بعد التخرج',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 7
            ],
            [
                'name' => 'صورة من جواز سفر الطبيب',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 8
            ],
            [
                'name' => 'صورة من وثيقة التأمين من هيئة التأمين الطبي',
                'for_registration' => true,
               'is_required' => 1,
               'order_number' => 9
            ],
            [
                'name' => 'صورة من التقرير بعد زيارة الطبيب الزائر',
                'for_registration' => false,
                'is_required' => true,
                'order_number' => 10
            ],
        ];

        foreach ($items as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'is_required' => $item['is_required'],
                'doctor_type' => 'visitor',
                'for_registration' => $item['for_registration'],
                'facility_type' => null,
                'order_number' => $item['order_number'] ?? 0,
            ]);
        }
    }
}
