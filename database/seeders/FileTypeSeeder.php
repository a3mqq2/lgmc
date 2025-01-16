<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FileType;

class FileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fileTypes = [
            // أنواع مستندات خاصة بالأطباء
            ['name' => 'شهادة البكالوريوس', 'type' => 'doctor', 'doctor_rank_id' => null, 'doctor_type' => 'libyan', 'is_required' => true],
            ['name' => 'شهادة الماجستير', 'type' => 'doctor', 'doctor_rank_id' => null, 'doctor_type' => 'foreign', 'is_required' => false],
            ['name' => 'جواز السفر', 'type' => 'doctor', 'doctor_rank_id' => null, 'doctor_type' => 'visitor', 'is_required' => true],
            ['name' => 'رقم التسجيل النقابي', 'type' => 'doctor', 'doctor_rank_id' => null, 'doctor_type' => 'libyan', 'is_required' => false],

            // أنواع مستندات خاصة بالمنشآت الطبية
            ['name' => 'ترخيص المنشأة', 'type' => 'medical_facility', 'doctor_rank_id' => null, 'doctor_type' => null, 'is_required' => true],
            ['name' => 'سجل تجاري', 'type' => 'medical_facility', 'doctor_rank_id' => null, 'doctor_type' => null, 'is_required' => true],
            ['name' => 'شهادة الضمان الصحي', 'type' => 'medical_facility', 'doctor_rank_id' => null, 'doctor_type' => null, 'is_required' => false],
        ];

        foreach ($fileTypes as $fileType) {
            FileType::updateOrCreate(
                ['name' => $fileType['name']], // شرط البحث والتحديث
                $fileType // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
