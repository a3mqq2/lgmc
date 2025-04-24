<?php
// app/Console/Commands/SyncLicenceCodes.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Licence;

class SyncLicenceCodes extends Command
{
    protected $signature   = 'fix:licence-codes';
    protected $description = 'Re-index licences per branch-year and rebuild their codes';

    public function handle(): int
    {
        DB::transaction(function () {
            Branch::with('licences')->chunkById(100, function ($branches) {
                foreach ($branches as $branch) {
                    $branch->licences
                        ->sortBy('created_at')
                        ->groupBy(fn ($l) => $l->created_at->year)
                        ->each(function ($group, $year) use ($branch) {
                            $i = 1;
                            foreach ($group as $licence) {
                                $licence->index = $i;
                                $licence->code  = $branch->code
                                                  . '-LIC-'
                                                  . $year
                                                  . '-'
                                                  . str_pad($i, 3, '0', STR_PAD_LEFT);
                                $licence->saveQuietly();
                                $i++;
                            }
                        });
                }
            });
        });

        $this->info('Licence indices and codes synced successfully.');
        return self::SUCCESS;
    }
}
