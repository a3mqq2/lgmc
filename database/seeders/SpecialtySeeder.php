<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            // تخصصات رئيسية
            ['name' => 'اطفال', 'specialty_id' => null],
            ['name' => ' نساء والتوليد', 'specialty_id' => null],
            ['name' => 'جراحة', 'specialty_id' => null],
            ['name' => 'اسنان', 'specialty_id' => null],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // شرط البحث والتحديث
                $specialty // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
