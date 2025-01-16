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
        // تعريف جميع أنواع المنشآت الطبية مع الأسماء بالعربية والإنجليزية
        $medicalFacilityTypes = [
            [
                'name' => 'مستشفى',
                'en_name' => 'Hospital',
            ],
            [
                'name' => 'عيادة',
                'en_name' => 'Clinic',
            ],
            [
                'name' => 'مركز الرعاية الصحية الأولية',
                'en_name' => 'Primary Healthcare Center',
            ],
            [
                'name' => 'مركز تخصصي',
                'en_name' => 'Specialized Center',
            ],
            [
                'name' => 'مركز التأهيل وإعادة التأهيل',
                'en_name' => 'Rehabilitation Center',
            ],
            [
                'name' => 'مركز الطوارئ',
                'en_name' => 'Emergency Center',
            ],
            [
                'name' => 'صيدلية',
                'en_name' => 'Pharmacy',
            ],
            [
                'name' => 'مركز الأشعة والتصوير الطبي',
                'en_name' => 'Radiology and Imaging Center',
            ],
            [
                'name' => 'مختبر طبي',
                'en_name' => 'Medical Laboratory',
            ],
            [
                'name' => 'دار رعاية المسنين',
                'en_name' => 'Nursing Home',
            ],
            [
                'name' => 'مركز الصحة النفسية',
                'en_name' => 'Mental Health Center',
            ],
            [
                'name' => 'مركز البحث الطبي',
                'en_name' => 'Medical Research Center',
            ],
            [
                'name' => 'مركز الرعاية المنزلية',
                'en_name' => 'Home Care Center',
            ],
            [
                'name' => 'مركز الصحة المجتمعية',
                'en_name' => 'Community Health Center',
            ],
            [
                'name' => 'مركز بيطري',
                'en_name' => 'Veterinary Center',
            ],
        ];

        // إدراج كل نوع من أنواع المنشآت الطبية في قاعدة البيانات
        foreach ($medicalFacilityTypes as $type) {
            MedicalFacilityType::firstOrCreate(
                ['en_name' => $type['en_name']], // الشرط للبحث عن السجل الحالي
                [
                    'name' => $type['name'],
                    'en_name' => $type['en_name'],
                ]
            );
        }

        $this->command->info('تم إضافة أنواع المنشآت الطبية بنجاح!');
    }
}
