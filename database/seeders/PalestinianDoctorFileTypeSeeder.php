<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class PalestinianDoctorFileTypeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name' => 'صورة شخصية',                                                         'slug' => 'personal_image',               'is_required' => true],
            ['name' => 'إفادة التخرج',                                                       'slug' => 'graduation_affidavit',         'is_required' => true],
            ['name' => 'كشف الدرجات',                                                        'slug' => 'grade_statement',              'is_required' => true],
            ['name' => 'شهادة الامتياز',                                                     'slug' => 'internship_certificate',       'is_required' => true],
            ['name' => 'صورة طبق الأصل للشهادة',                                              'slug' => 'certified_certificate_copy',   'is_required' => true],
            ['name' => 'معادلة الشهادة',                                                     'slug' => 'degree_equivalency',           'is_required' => true],
            ['name' => 'حسن سيرة وسلوك من الدولة المتخرج منها الطبيب',                        'slug' => 'good_conduct_certificate',     'is_required' => true],
            ['name' => 'صورة من عقد العمل',                                                  'slug' => 'employment_contract',          'is_required' => true],
            ['name' => 'رسالة من جهة العمل',                                                 'slug' => 'employment_letter',            'is_required' => true],
            ['name' => 'جواز سفر ساري المفعول',                                              'slug' => 'passport',                     'is_required' => true],
            ['name' => 'إقامة سارية المفعول فقط',                                             'slug' => 'valid_residence',              'is_required' => true],
            ['name' => 'ورقة  من السفارة الفلسطينية - لإثبات عضوية في الاتحاد الفلسطيني',     'slug' => 'palestinian_union_membership', 'is_required' => true],
            ['name' => 'صورة من إذن مزاولة  سابق للطبيب (ان وجد)  ',                         'slug' => 'previous_practice_license',    'is_required' => false],
        ];

        $index = 1;
        foreach ($items as $item) {
            FileType::create([
                'type'             => 'doctor',
                'name'             => $item['name'],
                'slug'             => $item['slug'],
                'is_required'      => $item['is_required'],
                'doctor_type'      => 'palestinian',
                'for_registration' => true,
                'facility_type'    => null,
                'order_number'     => $index++,
            ]);
        }
    }
}
