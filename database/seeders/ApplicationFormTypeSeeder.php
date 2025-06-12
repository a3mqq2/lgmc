<?php

namespace Database\Seeders;

use App\Models\FileType;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ApplicationFormTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileType::create([
            'type' => 'doctor',
            'name' => "نموذج انتساب",
            'is_required' => false,
            'for_registration' => false,
            'facility_type' => null,
            'doctor_type' => null,
            'order_number' => 999999,
        ]);
    }
}
