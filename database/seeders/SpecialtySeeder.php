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
            ['name' => 'الطب العام', 'specialty_id' => null],
            ['name' => 'طب الأطفال', 'specialty_id' => null],
            ['name' => 'طب النساء والتوليد', 'specialty_id' => null],
            ['name' => 'الجراحة العامة', 'specialty_id' => null],
            ['name' => 'طب الأسنان', 'specialty_id' => null],

            // تخصصات فرعية
            ['name' => 'جراحة المخ والأعصاب', 'specialty_id' => 4], // فرع للجراحة العامة
            ['name' => 'أمراض القلب', 'specialty_id' => 1], // فرع للطب العام
            ['name' => 'جراحة الأطفال', 'specialty_id' => 2], // فرع لطب الأطفال
            ['name' => 'تقويم الأسنان', 'specialty_id' => 5], // فرع لطب الأسنان
            ['name' => 'علاج العقم', 'specialty_id' => 3], // فرع لطب النساء والتوليد
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // شرط البحث والتحديث
                $specialty // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
