<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Country;

class ImportOldCountries extends Command
{
    protected $signature = '    ';
    protected $description = 'Import countries from lgmc_r.countries into LGMC.countries';

    public function handle()
    {
        $oldCountries = DB::connection('lgmc_r')->table('countries')->get();

        foreach ($oldCountries as $old) {
            try {
                $exists = Country::where('name', $old->country_arabic)
                    ->orWhere('en_name', $old->country_english)
                    ->first();

                if (!$exists) {
                    Country::create([
                        'name' => $old->country_arabic,
                        'en_name' => $old->country_english,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    $this->info("  Imported: {$old->country_arabic}");
                } else {
                    $this->line(" Skipped (already exists): {$old->country_arabic}");
                }
            } catch (\Exception $e) {
                $this->error(" Failed to import: {$old->country_arabic} - " . $e->getMessage());
            }
        }

        $this->info(" All countries imported successfully.");
    }
}
