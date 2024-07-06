<?php

namespace App\Console\Commands;

use App\Models\Licence;
use Carbon\Carbon;
use Illuminate\Console\Command;

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
        // Retrieve licences that are active and have expired
        $expiredLicences = Licence::where('status', 'active')
                                  ->where('expiry_date', '<=', Carbon::today())
                                  ->get();

        // Update the status of the expired licences
        foreach ($expiredLicences as $licence) {
            $licence->status = 'expired';
            $licence->save();
        }

        // Output the number of licences updated
        $this->info(count($expiredLicences) . ' licences have been updated to expired status.');
    }
}
