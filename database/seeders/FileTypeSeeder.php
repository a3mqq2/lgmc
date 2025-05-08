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
        ];

        foreach ($fileTypes as $fileType) {
            FileType::updateOrCreate(
                ['name' => $fileType['name']], // شرط البحث والتحديث
                $fileType // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
