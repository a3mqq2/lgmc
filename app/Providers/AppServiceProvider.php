<?php

namespace App\Providers;

use App\Models\Doctor;
use App\Models\Licence;
use App\Models\Pricing;
use App\Observers\PricingObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        
        Paginator::useBootstrap(); // Enable Bootstrap pagination style
        // View::share();
    }
}
