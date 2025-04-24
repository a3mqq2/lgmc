<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Branch;

class SyncDoctorIndices extends Command
{
    protected $signature = 'fix:doctor-codes';
    protected $description = 'Re-index doctors per branch and regenerate their codes';

    public function handle(): int
    {
        DB::transaction(function () {
            Branch::with(['doctors' => fn ($q) => $q->orderBy('id')])
                ->chunk(100, function ($branches) {
                    foreach ($branches as $branch) {
                        $i = 1;
                        foreach ($branch->doctors as $doctor) {
                            $doctor->index = $i;
                            $doctor->code  = $branch->code . '-DR-' . str_pad($i, 3, '0', STR_PAD_LEFT);
                            $doctor->saveQuietly();
                            $i++;
                        }
                    }
                });
        });

        $this->info('Doctor indices and codes synced successfully.');
        return self::SUCCESS;
    }
}
