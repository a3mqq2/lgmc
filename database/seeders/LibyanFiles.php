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
            ['name' => 'الصورة الشخصية', 'is_required' => true],
            ['name' => 'نموذج الانتساب', 'is_required' => true],
            ['name' => 'شهادة امتياز', 'is_required' => true],
            ['name' => 'كشف درجات', 'is_required' => true],
            ['name' => 'جواز سفر', 'is_required' => true],
            ['name' => 'شهادة ميلاد إلكترونية', 'is_required' => true],
            ['name' => 'رسالة عمل', 'is_required' => false], // اختيارية
            ['name' => 'شهادة تخصص', 'is_required' => false], // اختيارية
            ['name' => 'معادلة مجلس التخصصات الطبية', 'is_required' => false], // اختيارية
            ['name' => 'معادلة مركز ضمان الجودة', 'is_required' => false], // اختيارية
        ];

        foreach ($items as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'is_required' => $item['is_required'],
                'doctor_type' => 'libyan',
                'for_registration' => true,
                'facility_type' => null,
            ]);
        }
    }
}
