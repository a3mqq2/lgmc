<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AcademicDegree;

class AcademicDegreeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $degrees = [
            ['name' => 'بكالوريوس'],
            ['name' => 'ماجستير'],
            ['name' => 'دكتوراه'],
            ['name' => 'زمالة'],
        ];

        foreach ($degrees as $degree) {
            AcademicDegree::updateOrCreate(
                ['name' => $degree['name']] // شرط البحث والتحديث
            );
        }

        $this->command->info('✅ تم إنشاء الدرجات العلمية بنجاح!');
    }
}