<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FinancialCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'رسوم عضوية',
            ],
            [
                'name' => 'اذن مزاولة',
            ],
            [
                'name' => 'اوراق بالخارج'
            ],
            [
                'name' => 'اغلاق الخزينة'
            ],
            [
                'name' => 'تحويل اموال'
            ]
        ];

        foreach ($data as $item) {
            \App\Models\FinancialCategory::create([
                'name' => $item['name'],
                'type' => 'deposit',
            ]);
        }
    }
}
