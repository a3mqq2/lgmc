<?php

namespace App\Observers;

use App\Models\Pricing;
use App\Models\Log;

class PricingObserver
{
    /**
     * Handle the Pricing "created" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function created(Pricing $pricing)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم إضافة تسعيرة جديدة: " . $pricing->name,
        ]);
    }

    /**
     * Handle the Pricing "updated" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function updated(Pricing $pricing)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم تحديث تسعيرة: " . $pricing->name,
        ]);
    }

    /**
     * Handle the Pricing "deleted" event.
     *
     * @param  \App\Models\Pricing  $pricing
     * @return void
     */
    public function deleted(Pricing $pricing)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم حذف تسعيرة: " . $pricing->name,
        ]);
    }
}
