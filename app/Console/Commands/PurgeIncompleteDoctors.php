<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Carbon\Carbon;
use App\Models\Doctor;

class PurgeIncompleteDoctors extends Command
{
    protected $signature   = 'doctor:purge-incomplete';
    protected $description = 'Delete doctors that have no index OR no code and are older than 90 days';

    public function handle(): void
    {
        $cutoff = Carbon::now()->subDays(90);

        $deleted = Doctor::where(function ($q) {
                $q->whereNull('index')
                  ->orWhereNull('code');
            })
            ->where('created_at', '<=', $cutoff)
            ->delete();

        $this->info("✅ Deleted {$deleted} incomplete doctor records older than 90 days.");
    }
}
