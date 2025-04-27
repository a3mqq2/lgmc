<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class SyncMedicalFacilityIndices extends Command
{
    protected $signature = 'fix:medical-facility-codes';
    protected $description = 'Re-index medical facilities per branch and regenerate their codes';

    public function handle(): int
    {
        DB::transaction(function () {
            Branch::with(['medicalFacilities' => fn ($q) => $q->orderBy('id')])
                ->chunk(100, function ($branches) {
                    foreach ($branches as $branch) {
                        $i = 1;
                        foreach ($branch->medicalFacilities as $facility) {
                            $facility->index = $i;
                            $facility->code  = $branch->code . '-MF-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            $facility->saveQuietly();
                            $i++;
                        }
                    }
                });
        });

        $this->info('Medical facility indices and codes synced successfully.');
        return self::SUCCESS;
    }
}
