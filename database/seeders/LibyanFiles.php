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
            ['name' => 'الصورة الشخصية', 'is_required' => true, 'order_number' => 1],
            ['name' => 'شهادة امتياز', 'is_required' => true, 'order_number' => 3],
            ['name' => 'كشف درجات', 'is_required' => true, 'order_number' => 4],
            ['name' => 'جواز سفر', 'is_required' => true, 'order_number' => 5],
            ['name' => 'شهادة ميلاد إلكترونية', 'is_required' => true, 'order_number' => 6],
            ['name' => 'رسالة عمل', 'is_required' => false, 'order_number' => 7], // اختيارية
            ['name' => 'شهادة تخصص', 'is_required' => false, 'order_number' => 8], // اختيارية
            ['name' => 'معادلة مجلس التخصصات الطبية', 'is_required' => false, 'order_number' => 9], // اختيارية
            ['name' => 'معادلة مركز ضمان الجودة', 'is_required' => false, 'order_number' => 10], // اختيارية
        ];

        foreach ($items as $item) {
            FileType::create([
                'type' => 'doctor',
                'name' => $item['name'],
                'is_required' => $item['is_required'],
                'doctor_type' => 'libyan',
                'for_registration' => true,
                'facility_type' => null,
                'order_number' => $item['order_number'],
            ]);
        }
    }
}
