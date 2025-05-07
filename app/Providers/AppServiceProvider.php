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
        ini_set('upload_max_filesize', '634M');
        ini_set('post_max_size', '643M');
    }
}
