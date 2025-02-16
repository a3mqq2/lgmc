<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Otp;
use App\Models\Doctor;
use Carbon\Carbon;

class OtpController extends Controller
{
    /**
     * Show OTP verification form.
     */
    public function showVerifyOtpForm(Request $request)
    {
        return view('verify-otp', ['email' => $request->email]);
    }

    /**
     * Verify OTP code.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|digits:6',
        ]);

        // Retrieve OTP from database
        $otp = Otp::where('email', $request->email)
            ->where('otp_code', $request->otp_code)
            ->where('is_verified', false)
            ->where('expires_at', '>=', Carbon::now())
            ->first();


        if (!$otp) {
            return redirect()->back()->withErrors(['otp_code' => 'رمز التحقق غير صحيح أو منتهي الصلاحية.']);
        }

        // Mark OTP as verified
        $otp->update(['is_verified' => true]);

        // Optionally activate doctor account after OTP verification
        $doctor = Doctor::where('email', $request->email)->first();
        if ($doctor) {
            $doctor->email_verified_at = Carbon::now();
            $doctor->save();
        }

        return redirect()->route('doctor-login')->with('success', 'تم التحقق من البريد الإلكتروني بنجاح، يمكنك الآن تسجيل الدخول.');
    }
}
