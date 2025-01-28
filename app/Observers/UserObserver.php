<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Log;

class UserObserver
{
    /**
     * Handle the User "created" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function created(User $user)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم إنشاء الموظف: " . $user->name,
        ]);
    }

    /**
     * Handle the User "updated" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function updated(User $user)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم تحديث بيانات الموظف: " . $user->name,
        ]);
    }

    /**
     * Handle the User "deleted" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function deleted(User $user)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم حذف الموظف: " . $user->name,
        ]);
    }

    /**
     * Handle the User "status changed" event.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function statusChanged(User $user)
    {
        Log::create([
            'user_id' => auth()->user()->id,
            'details' => "تم تغيير حالة الموظف: " . $user->name,
        ]);
    }
}
