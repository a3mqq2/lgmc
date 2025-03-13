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
            ['name' => 'طب الأطفال', 'specialty_id' => null],
            ['name' => 'أمراض النساء والتوليد', 'specialty_id' => null],
            ['name' => 'جراحة المسالك', 'specialty_id' => null],
            ['name' => 'جراحة العظام', 'specialty_id' => null],
            ['name' => 'أمراض باطنة', 'specialty_id' => null],
            ['name' => 'طب العيون', 'specialty_id' => null],
            ['name' => 'طب انف واذن وحنجرة', 'specialty_id' => null],
            ['name' => 'طب تخدير', 'specialty_id' => null],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // شرط البحث والتحديث
                $specialty // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
