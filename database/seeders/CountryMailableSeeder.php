<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CountryMailableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arabicCountries = [
            'الإمارات العربية المتحدة',
            'عمان',
            'قطر',
            'جنوب أفريقيا',
            'ألمانيا',
            'كندا',
            'المملكة المتحدة',
            'أيرلندا',
            'الولايات المتحدة',
        ];

        DB::table('countries')
            ->whereIn('country_name_ar', $arabicCountries)
            ->update(['mailable' => 1]);
    }
}
