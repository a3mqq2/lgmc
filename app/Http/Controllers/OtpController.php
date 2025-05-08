<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Otp;
use App\Models\Doctor;
use App\Mail\VerifyOtp;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

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

        // redirect to upload documents page
        return redirect()->route('upload-documents', ['email' => $request->email])->with('success', 'تم التحقق من رمز OTP بنجاح.');
    }


    public function resendOtp(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|exists:doctors,email',
        ]);

        Otp::where('email', $data['email'])
           ->where('is_verified', false)
           ->delete();

        $otpCode = rand(100000, 999999);
        Otp::create([
            'email'      => $data['email'],
            'otp_code'   => $otpCode,
            'is_verified'=> false,
            'expires_at' => Carbon::now()->addMinutes(30),
        ]);
        try {
            Mail::to($data['email'])
                ->send(new VerifyOtp($otpCode, $data['email']));
        } catch (\Exception $e) {
            Log::error('Error resending OTP: ' . $e->getMessage());
            return back()->withErrors('حدث خطأ أثناء إعادة الإرسال.');
        }

        return back()->with('success', 'تم إعادة إرسال رمز التحقق إلى بريدك.');
    }
}
