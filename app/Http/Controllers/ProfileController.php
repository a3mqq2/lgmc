<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Log;

class ProfileController extends Controller
{
    public function change_password()
    {
        return view('general.profile.password');
    }

    public function change_password_store(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        // التحقق من كلمة المرور الحالية
        if (!Hash::check($request->current_password, auth()->user()->password)) {
            // سجل محاولة فاشلة لتغيير كلمة المرور
            Log::create([
                'user_id' => auth()->id(),
                'details' => 'فشل في محاولة تغيير كلمة المرور بسبب كلمة مرور حالية غير صحيحة',
                'loggable_id' => auth()->id(),
                'loggable_type' => \App\Models\User::class,
            ]);

            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        // تحديث كلمة المرور
        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        // سجل تغيير كلمة المرور الناجح
        Log::create([
            'user_id' => auth()->id(),
            'details' => 'تم تغيير كلمة المرور بنجاح',
            'loggable_id' => auth()->id(),
            'loggable_type' => \App\Models\User::class,
        ]);

        return redirect()->to(url()->previous() . '?change-password=1')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }
}
