<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\University;

class ArabUniversitiesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            'جامعة القاهرة',
            'جامعة الملك سعود',
            'جامعة الأزهر',
            'جامعة عين شمس',
            'جامعة الخرطوم',
            'جامعة تونس',
            'جامعة الجزائر',
            'جامعة بغداد',
            'جامعة الكويت',
            'جامعة الشارقة',
            'جامعة الملك عبد العزيز',
            'جامعة السلطان قابوس',
            'جامعة قطر',
            'جامعة بيروت العربية',
            'جامعة الإمارات العربية المتحدة',
            'جامعة الأردن',
            'جامعة اليرموك',
            'جامعة نواكشوط',
            'جامعة طرابلس',
            'جامعة بنغازي',
        ];

        foreach ($universities as $name) {
            University::create(['name' => $name]);
        }
    }
}
