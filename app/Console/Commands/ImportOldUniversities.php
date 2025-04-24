<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\University;

class ImportOldUniversities extends Command
{
    protected $signature = 'import:universities';
    protected $description = 'Import universities from old database lgmc_r';

    public function handle()
    {
        $oldUniversities = DB::connection('lgmc_r')->table('universities')->get();

        foreach ($oldUniversities as $old) {
            try {
                University::create([
                    'id' => $old->id,
                    'name' => $old->name,
                    'en_name' => $old->name_en,
                    'created_at' => $old->created_at === '0000-00-00 00:00:00' ? now() : $old->created_at,
                    'updated_at' => $old->updated_at === '0000-00-00 00:00:00' ? now() : $old->updated_at,
                ]);

                $this->info("✔️ Imported: {$old->name}");
            } catch (\Exception $e) {
                $this->error("❌ Failed to import ID {$old->id}: " . $e->getMessage());
            }
        }

        $this->info("🎉 All universities imported successfully.");
    }
}
