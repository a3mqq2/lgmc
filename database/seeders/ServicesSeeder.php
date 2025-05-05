<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pricing = [
            ['name' => 'البريد الالكتروني', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'libyan'],
            ['name' => 'البريد الالكتروني', 'amount' => 50, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'palestinian'],
            ['name' => 'البريد الالكتروني', 'amount' => 100, 'type' => 'service', 'entity_type' => 'doctor', 'doctor_type' => 'foreign']
        ];


        foreach ($pricing as $item) {
            \App\Models\Pricing::create($item);
        }
    }
}
