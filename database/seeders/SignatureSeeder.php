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
                'job_title' => 'Secretary General',
                'job_title_ar' => 'النقيب العام',
                'is_selected' => 1,
                'branch_id' => null,
            ],
            [
                'name' => "د. صالح أحمد صالح الأشطر",
                'name_en' => 'Saleh Ahmed Saleh Al-Ashtar',
                'job_title_ar' => 'نائب نقيب أطباء طرابلس',
                'job_title' => 'Deputy Secretary General of Tripoli Doctors',
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
