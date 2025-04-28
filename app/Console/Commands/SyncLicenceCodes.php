<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;
use App\Models\Licence;

class SyncLicenceCodes extends Command
{
    protected $signature   = 'fix:licence-codes';
    protected $description = 'Re-index licences per branch-issued-year and rebuild their codes correctly';

    public function handle(): int
    {
        DB::transaction(function () {
            Branch::chunkById(100, function ($branches) {
                foreach ($branches as $branch) {
                    // اسحب كل التراخيص لهذا الفرع
                    $licences = Licence::where('branch_id', $branch->id)
                        ->orderBy('issued_date')
                        ->get()
                        ->groupBy(function ($licence) {
                            // تصحيح تحديد السنة
                            if ($licence->issued_date) {
                                return $licence->issued_date->format('Y');
                            } elseif ($licence->expiry_date) {
                                return $licence->expiry_date->copy()->addDay()->subYear()->format('Y');
                            } else {
                                return now()->year;
                            }
                        });

                    foreach ($licences as $year => $yearLicences) {
                        $i = 1;
                        foreach ($yearLicences as $licence) {
                            $prefix = $licence->licensable_type === \App\Models\Doctor::class ? 'LIC' : 'PERM';

                            $licence->index = $i;
                            $licence->code = $branch->code
                                            . '-' . $prefix . '-'
                                            . $year
                                            . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            $licence->saveQuietly();
                            $i++;
                        }
                    }
                }
            });
        });

        $this->info('Licence indices and codes synced correctly per real year.');
        return self::SUCCESS;
    }
}
