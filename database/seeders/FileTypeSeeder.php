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
            ['name' => 'الصورة الشخصية', 'type' => 'doctor', 'doctor_rank_id' => null, 'is_required' => true],
        ];

        foreach ($fileTypes as $fileType) {
            FileType::updateOrCreate(
                ['name' => $fileType['name']], // شرط البحث والتحديث
                $fileType // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
