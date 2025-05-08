<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Licence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileType;
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
        if(!Auth::guard('doctor')->user()->email_verified_at)
        {
          return  redirect()->route('otp.verify', ['email' => $request->email]);
        }

        // check files

        if(!Auth::guard('doctor')->user()->documents_completed)
        {
            return redirect()->route('upload-documents', ['email' => $request->email]);
        }


        return redirect()->route('doctor.dashboard')
                         ->with('success', 'تم تسجيل الدخول بنجاح.');


        
    } else {
        return redirect()->back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ]);
    }

}


    public function checker()
    {
        $doctor = Doctor::where('code', request('licence'))->first();
        return view('website.checker', compact('doctor'));
    }



        public function upload_documents(Request $request)
        {
            $doctor = Doctor::where('membership_status', 'pending')
                            ->where('email', $request->email)
                            ->first();

            if (! $doctor) {
                return redirect()->back()->withErrors([
                    'email' => 'يرجى التأكد من بيانات الحساب'
                ]);
            }

            // علِّم البريد كمفعّل
            $doctor->email_verified_at = now();
            $doctor->save();

            // جميع أنواع الملفات المطلوبة للطبيب
            $file_types = FileType::where('doctor_type', $doctor->type->value)
            ->where('for_registration', 1)
                                ->get();

            // أنواع الملفات التي رفعها الطبيب مسبقًا
            $uploadedTypeIds = $doctor->files()
                                    ->pluck('file_type_id')
                                    ->toArray();

            // أنواع الملفات التي لا يزال لم تُرفع بعد
            $missingFileTypes = $file_types->filter(function($ft) use ($uploadedTypeIds) {
                return ! in_array($ft->id, $uploadedTypeIds);
            });

            return view('upload-documents', [
                'doctor'            => $doctor,
                'missingFileTypes'  => $missingFileTypes,
                'alreadyUploaded'   => $uploadedTypeIds,
            ]);
        }

        public function filepond_process(Request $request)
        {
            $allFiles = $request->allFiles();
            if (empty($allFiles)) {
                return response('No file uploaded', 400);
            }
            $file = array_shift($allFiles);
    
            $request->validate([
                'doctor_id'    => 'required|integer|exists:doctors,id',
                'file_type_id' => 'required|integer|exists:file_types,id',
            ]);
    
            $doctor     = Doctor::findOrFail($request->doctor_id);
            $fileTypeId = $request->file_type_id;
            $path       = $file->store("documents/{$doctor->id}", 'public');
    
            $doctor->files()->updateOrCreate(
                [
                    'doctor_id'    => $doctor->id,
                    'file_type_id' => $fileTypeId,
                ],
                [
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]
            );
    
            $requiredTypeIds = FileType::where('doctor_type', $doctor->type->value)
                ->where('is_required', true)
                ->pluck('id')
                ->toArray();
    
            $uploadedCount = $doctor->files()
                ->whereIn('file_type_id', $requiredTypeIds)
                ->distinct()
                ->count('file_type_id');
    
            if ($uploadedCount === count($requiredTypeIds)) {
                $doctor->documents_completed = true;
                $doctor->registered_at = now();
                $doctor->save();
            }
    
            return response($path, 200);
        }
    
    
    public function complete_registration(Request $request, Doctor $doctor)
    {

        
        $doctor = Doctor::find($doctor->id);
        $doctor->registered_at = now();
        $doctor->documents_completed = true;
        $doctor->save();

        return redirect()->route('doctor-login')
                         ->with('success', 'تم إكمال التسجيل بنجاح.');
    }
}
