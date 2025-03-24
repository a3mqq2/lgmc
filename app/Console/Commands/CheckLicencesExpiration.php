<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Licence;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CheckLicencesExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'licences:expiration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check if any licences have expired and update their status.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredCount = Licence::where('status', 'active')
                               ->where('expiry_date', '<=', Carbon::today())
                               ->update(['status' => 'expired']);
    
        Log::info("{$expiredCount} licences expired on " . now()->toDateString());
    
        $this->info($expiredCount . ' licences have been updated to expired status.');
    }
}
