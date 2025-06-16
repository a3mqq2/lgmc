<?php

namespace Database\Seeders;

use App\Models\FileType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LibyanFiles extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            ['name' => 'الصورة الشخصية', 'is_required' => true, 'order_number' => 1, 'slug' => 'personal_image', 'for_registration' => true],
            ['name' => 'نموذج انتساب', 'is_required' => false, 'order_number' => 2, 'slug' => 'application_form', 'for_registration' => false],
            ['name' => 'شهادة امتياز', 'is_required' => true, 'order_number' => 3, 'slug' => 'internship_certificate', 'for_registration' => true],
            ['name' => 'كشف درجات', 'is_required' => true, 'order_number' => 4, 'slug' => 'graduation_statement', 'for_registration' => true],
            ['name' => 'جواز سفر', 'is_required' => true, 'order_number' => 5, 'slug' => 'passport', 'for_registration' => true],
            ['name' => 'شهادة ميلاد إلكترونية', 'is_required' => true, 'order_number' => 6, 'for_registration' => true, 'slug' => 'other'],
            ['name' => 'رسالة عمل', 'is_required' => false, 'order_number' => 7, 'for_registration' => true, 'slug' => 'employment_letter'], // اختيارية
            ['name' => 'شهادة تخصص', 'is_required' => false, 'order_number' => 8, 'for_registration' => true, 'slug' => 'other'], // اختيارية
            ['name' => 'معادلة مجلس التخصصات الطبية', 'is_required' => false, 'order_number' => 9, 'for_registration' => true, 'slug' => 'other'], // اختيارية
            ['name' => 'معادلة مركز ضمان الجودة', 'is_required' => false, 'order_number' => 10, 'for_registration' => true, 'slug' => 'other'], // اختيارية
            ['name' => 'إقامة', 'is_required' => false, 'order_number' => 11, 'for_registration' => false, 'slug' => 'accommodation'], // اختيارية
            ['name' => 'كتيب العائلة', 'is_required' => false, 'order_number' => 12, 'for_registration' => false, 'slug' => 'family_paper'], // اختيارية
        ];

        foreach ($items as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'is_required' => $item['is_required'],
                'doctor_type' => 'libyan',
                'for_registration' => $item['for_registration'],
                'facility_type' => null,
                'order_number' => $item['order_number'],
                'slug' => $item['slug'],
            ]);
        }
    }
}
