<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Country; // Assuming you have a Country model

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // First, insert Libya and Palestine
        $countries = [
            ['name' => 'ليبيا', 'en_name' => 'Libya'],
            ['name' => 'فلسطين', 'en_name' => 'Palestine']
        ];

        foreach ($countries as $country) {
            Country::create([
                'name' => $country['name'],
                'en_name' => $country['en_name'],
            ]);
        }

        // Arabic countries list
        $arabicCountries = [
            ['name' => 'الجزائر', 'en_name' => 'Algeria'],
            ['name' => 'البحرين', 'en_name' => 'Bahrain'],
            ['name' => 'جزر القمر', 'en_name' => 'Comoros'],
            ['name' => 'جيبوتي', 'en_name' => 'Djibouti'],
            ['name' => 'مصر', 'en_name' => 'Egypt'],
            ['name' => 'العراق', 'en_name' => 'Iraq'],
            ['name' => 'الأردن', 'en_name' => 'Jordan'],
            ['name' => 'الكويت', 'en_name' => 'Kuwait'],
            ['name' => 'لبنان', 'en_name' => 'Lebanon'],
            ['name' => 'ليبيا', 'en_name' => 'Libya'],
            ['name' => 'موريتانيا', 'en_name' => 'Mauritania'],
            ['name' => 'المغرب', 'en_name' => 'Morocco'],
            ['name' => 'عمان', 'en_name' => 'Oman'],
            ['name' => 'فلسطين', 'en_name' => 'Palestine'],
            ['name' => 'قطر', 'en_name' => 'Qatar'],
            ['name' => 'السعودية', 'en_name' => 'Saudi Arabia'],
            ['name' => 'الصومال', 'en_name' => 'Somalia'],
            ['name' => 'السودان', 'en_name' => 'Sudan'],
            ['name' => 'سوريا', 'en_name' => 'Syria'],
            ['name' => 'تونس', 'en_name' => 'Tunisia'],
            ['name' => 'الإمارات العربية المتحدة', 'en_name' => 'United Arab Emirates'],
            ['name' => 'اليمن', 'en_name' => 'Yemen']
        ];

        foreach ($arabicCountries as $country) {
            Country::create([
                'name' => $country['name'],
                'en_name' => $country['en_name'],
            ]);
        }

        // List of other countries (non-Arab countries)
        $otherCountries = [
            ['name' => 'الولايات المتحدة', 'en_name' => 'United States'],
            ['name' => 'كندا', 'en_name' => 'Canada'],
            ['name' => 'ألمانيا', 'en_name' => 'Germany'],
            ['name' => 'فرنسا', 'en_name' => 'France'],
            ['name' => 'المملكة المتحدة', 'en_name' => 'United Kingdom'],
            ['name' => 'إيطاليا', 'en_name' => 'Italy'],
            ['name' => 'روسيا', 'en_name' => 'Russia'],
            ['name' => 'الصين', 'en_name' => 'China'],
            ['name' => 'الهند', 'en_name' => 'India'],
            ['name' => 'أستراليا', 'en_name' => 'Australia'],
            ['name' => 'اليابان', 'en_name' => 'Japan'],
            ['name' => 'البرازيل', 'en_name' => 'Brazil'],
            ['name' => 'جنوب أفريقيا', 'en_name' => 'South Africa'],
            ['name' => 'المكسيك', 'en_name' => 'Mexico'],
            ['name' => 'كوريا الجنوبية', 'en_name' => 'South Korea'],
            ['name' => 'الأرجنتين', 'en_name' => 'Argentina'],
            ['name' => 'إسبانيا', 'en_name' => 'Spain'],
            ['name' => 'تركيا', 'en_name' => 'Turkey'],
            ['name' => 'اليونان', 'en_name' => 'Greece'],
            ['name' => 'هولندا', 'en_name' => 'Netherlands'],
            ['name' => 'السويد', 'en_name' => 'Sweden'],
            ['name' => 'بلجيكا', 'en_name' => 'Belgium'],
            ['name' => 'النرويج', 'en_name' => 'Norway'],
            ['name' => 'سويسرا', 'en_name' => 'Switzerland'],
            ['name' => 'فنلندا', 'en_name' => 'Finland'],
            ['name' => 'الدنمارك', 'en_name' => 'Denmark'],
            ['name' => 'بولندا', 'en_name' => 'Poland'],
            ['name' => 'البرتغال', 'en_name' => 'Portugal'],
            ['name' => 'التشيك', 'en_name' => 'Czech Republic'],
            ['name' => 'سلوفاكيا', 'en_name' => 'Slovakia'],
            ['name' => 'أوكرانيا', 'en_name' => 'Ukraine'],
            ['name' => 'رومانيا', 'en_name' => 'Romania'],
            ['name' => 'تشيلي', 'en_name' => 'Chile'],
            ['name' => 'نيوزيلندا', 'en_name' => 'New Zealand'],
        ];

        foreach ($otherCountries as $country) {
            Country::create([
                'name' => $country['name'],
                'en_name' => $country['en_name'],
            ]);
        }
    }
}
