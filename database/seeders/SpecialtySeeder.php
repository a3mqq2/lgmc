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
            ['name' => 'طب الأطفال', 'name_en' => 'Pediatrics'],
            ['name' => 'طب الأسرة', 'name_en' => 'Family Medicine'],
            ['name' => 'طب القلب', 'name_en' => 'Cardiology'],
            ['name' => 'طب الأعصاب', 'name_en' => 'Neurology'],
            ['name' => 'طب الأمراض الجلدية', 'name_en' => 'Dermatology'],
            ['name' => 'طب الأسنان', 'name_en' => 'Dentistry'],
            ['name' => 'طب الطوارئ', 'name_en' => 'Emergency Medicine'],
            ['name' => 'جراحة عامة', 'name_en' => 'General Surgery'],
            ['name' => 'جراحة العظام', 'name_en' => 'Orthopedic Surgery'],
            ['name' => 'جراحة القلب', 'name_en' => 'Cardiac Surgery'],
            ['name' => 'جراحة الأعصاب', 'name_en' => 'Neurosurgery'],
            ['name' => 'طب النساء والتوليد', 'name_en' => 'Obstetrics and Gynecology'],
            ['name' => 'طب العيون', 'name_en' => 'Ophthalmology'],
            ['name' => 'طب الأنف والأذن والحنجرة', 'name_en' => 'ENT (Ear, Nose, and Throat)'],
            ['name' => 'طب الأشعة', 'name_en' => 'Radiology'],
            ['name' => 'طب الأمراض النفسية والعصبية', 'name_en' => 'Psychiatry'],
            ['name' => 'طب الأورام', 'name_en' => 'Oncology'],
            ['name' => 'طب المسالك البولية', 'name_en' => 'Urology'],
            ['name' => 'طب الغدد الصماء', 'name_en' => 'Endocrinology'],
            ['name' => 'طب الأمراض المعدية', 'name_en' => 'Infectious Diseases'],
            ['name' => 'طب الرعاية الحرجة', 'name_en' => 'Critical Care Medicine'],
            ['name' => 'طب التخدير', 'name_en' => 'Anesthesiology'],
            ['name' => 'طب الطب الرياضي', 'name_en' => 'Sports Medicine'],
            ['name' => 'طب التغذية', 'name_en' => 'Nutrition'],
            ['name' => 'طب الطب الباطني', 'name_en' => 'Internal Medicine'],
            ['name' => 'طب الأمراض الصدرية', 'name_en' => 'Pulmonology'],
            ['name' => 'طب الأمراض الهضمية', 'name_en' => 'Gastroenterology'],
            ['name' => 'طب الأمراض الروماتيزمية', 'name_en' => 'Rheumatology'],
            ['name' => 'طب الأمراض الوراثية', 'name_en' => 'Genetics'],
            
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // شرط البحث والتحديث
                $specialty // القيم التي سيتم إضافتها أو تحديثها
            );
        }

    }
}
