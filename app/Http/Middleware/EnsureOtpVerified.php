<?php
// app/Http/Middleware/EnsureOtpVerified.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnsureOtpVerified
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $doctor = Auth::guard('doctor')->user();

        if (! $doctor || is_null($doctor->email_verified_at)) {



            return redirect()->route('otp.verify');
        }

        return $next($request);
    }
}
