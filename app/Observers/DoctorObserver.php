<?php

namespace App\Observers;

use App\Models\Doctor;
use App\Models\Log;

class DoctorObserver
{
    /**
     * Handle the Doctor "created" event.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return void
     */
    public function created(Doctor $doctor)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم إضافة الطبيب: " . $doctor->name,
        ]);
    }

    /**
     * Handle the Doctor "updated" event.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return void
     */
    public function updated(Doctor $doctor)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم تعديل بيانات الطبيب: " . $doctor->name,
        ]);
    }

    /**
     * Handle the Doctor "deleted" event.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return void
     */
    public function deleted(Doctor $doctor)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم حذف الطبيب: " . $doctor->name,
        ]);
    }

    /**
     * Handle the Doctor "approved" event.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return void
     */
    public function approved(Doctor $doctor)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم الموافقة على الطبيب: " . $doctor->name,
        ]);
    }
}
