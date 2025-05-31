<?php

namespace Database\Seeders;

use App\Models\Signature;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SignatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $names = [
            [
                'name' => 'د.محمد علي الغوج',
                'name_en' => 'mohammed ali alghoj',
                'job_title_ar' => 'Secretary General',
                'job_title' => 'النقيب العام',
                'is_selected' => 1,
                'branch_id' => null,
            ],
            [
                'name' => "د. خالد محمد",
                'name_en' => 'khaled mohamed',
                'job_title_ar' => 'نائب النقيب العام',
                'job_title' => 'Deputy General Secretary',
                'is_selected' => 1,
                'branch_id' => 1,
            ]
        ];


        foreach ($names as $name) {
            Signature::create([
                'name' => $name['name'],
                'name_en' => $name['name_en'],
                'job_title_ar' => $name['job_title_ar'],
                'job_title_en' => $name['job_title'],
                'branch_id' => $name['branch_id'],
                'is_selected' => $name['is_selected'],
            ]);
        }

    }
}
