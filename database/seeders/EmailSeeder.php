<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class EmailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $emails = ['info@hululit.ly', 'info@lgmc.ly'];
        foreach ($emails as $email) {
            \DB::table('emails')->insert([
                'email' => $email,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
