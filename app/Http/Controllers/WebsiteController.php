<?php

namespace App\Http\Controllers;

use App\Models\Licence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.index');
    }

    public function doctor_login()
    {
        return view('website.doctor_login');
    }

    public function doctor_auth(Request $request)
{
    // Validate the request
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    // Rate limiter key
    $throttleKey = Str::lower($request->input('email')) . '|' . $request->ip();

    if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
        throw ValidationException::withMessages([
            'email' => 'عدد المحاولات الفاشلة لتسجيل الدخول تجاوز الحد المسموح. الرجاء المحاولة لاحقاً.',
        ])->status(429);
    }

    // Credentials
    $credentials = $request->only('email', 'password');
    if (Auth::guard('doctor')->attempt($credentials, $request->boolean('remember'))) {
        // Successful login
        RateLimiter::clear($throttleKey); // Clear rate limiter on successful login
        return redirect()->route('doctor.dashboard')->with('success', 'تم تسجيل الدخول بنجاح.');
    }

    // Increment failed login attempts
    RateLimiter::hit($throttleKey, 60); // Add a failed attempt, with a decay time of 60 seconds

    throw ValidationException::withMessages([
        'email' => 'بيانات الدخول غير صحيحة. الرجاء التحقق من البريد الإلكتروني أو كلمة المرور.',
    ]);
}


    public function checker()
    {
        $licence = Licence::where('code', request('licence'))->first();
        return view('website.checker', compact('licence'));
    }
}
