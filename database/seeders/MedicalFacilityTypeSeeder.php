<?php

namespace Database\Seeders;

use App\Models\MedicalFacilityType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MedicalFacilityTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $medicalFacilityTypes = [
            [
                'name' => 'عيادة فردية ',
                'en_name' => 'Private Clinic',
            ],
            [
                'name' => 'خدمات طبية ',
                'en_name' => 'Medical Services',
            ],
        ];


        foreach ($medicalFacilityTypes as $type) {
            MedicalFacilityType::create([
                'name' => $type['name'],
                'en_name' => $type['en_name'],
            ]);
        }

        $this->command->info('تم إضافة أنواع المنشآت الطبية بنجاح!');
    }   
}
