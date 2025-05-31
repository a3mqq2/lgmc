<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Stichoza\GoogleTranslate\GoogleTranslate;
use App\Models\Country;
use App\Models\University;

class ArabUniversitiesSeeder extends Seeder
{
    public function run()
    {
        $translator = new GoogleTranslate('ar');

        $countries = Country::all();

        foreach ($countries as $country) {
            try {
                $response = Http::timeout(20)->retry(3, 1000)->get('http://universities.hipolabs.com/search', [
                    'country' => $country->country_name_en,
                ]);
            } catch (\Exception $e) {
                $this->command->error("Request failed for {$country->country_name_en}: " . $e->getMessage());
                continue;
            }

            if (! $response->successful()) {
                $this->command->error("Failed to fetch universities for {$country->en_name}");
                continue;
            }

            $universities = $response->json();

            foreach ($universities as $uni) {
                if (empty($uni['name'])) {
                    continue;
                }

                try {
                    $nameAr = $translator->translate($uni['name']);
                } catch (\Exception $e) {
                    $nameAr = $uni['name'];
                }

                University::firstOrCreate(
                    [
                        'en_name'    => $uni['name'],
                        'country_id' => $country->id,
                    ],
                    [
                        'name' => $nameAr,
                    ]
                );
            }

            $this->command->info("Seeded universities for {$country->en_name}");
        }
    }
}
