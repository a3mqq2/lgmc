<?php

namespace App\Console\Commands;

use App\Models\Doctor;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ExpireMemberships extends Command
{
    protected $signature = 'doctors:expire-memberships';
    protected $description = 'Mark doctors with expired memberships as expired';

    public function handle(): int
    {
        Doctor::whereDate('membership_expiration_date', '<=', Carbon::today())
              ->where('membership_status', '!=', 'inactive')
              ->update(['membership_status' => 'inactive']);

        return self::SUCCESS;
    }
}
