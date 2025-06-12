<?php

namespace Database\Seeders;

use App\Models\FileType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherFileTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileType::create([
            'type' => 'doctor',
            'name' => "اخرى",
            'is_required' => false,
            'for_registration' => false,
            'facility_type' => null,
            'doctor_type' => null,
            'order_number' => 99999,
        ]);
    }
}
