<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Licence;
use App\Models\FileType;
use App\Models\DoctorFile;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityFile;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        // Validate the request: allow email or phone
        $request->validate([
            'login'    => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        $login = $request->input('login');

        // Determine whether login is email or phone
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        // Rate limiter key uses the login value
        $throttleKey = Str::lower($login) . '|' . $request->ip();

        if (RateLimiter::tooManyAttempts($throttleKey, 5)) {
            throw ValidationException::withMessages([
                'login' => 'عدد المحاولات الفاشلة لتسجيل الدخول تجاوز الحد المسموح. الرجاء المحاولة لاحقاً.',
            ])->status(429);
        }

        // Build credentials dynamically
        $credentials = [
            $field    => $login,
            'password' => $request->input('password'),
        ];

        if (Auth::guard('doctor')->attempt($credentials, $request->boolean('remember'))) {
            // Clear the rate limiter on successful login
            RateLimiter::clear($throttleKey);

            $doctor = Auth::guard('doctor')->user();

            // Redirect to OTP verify if email not verified
            if (! $doctor->email_verified_at) {
                return redirect()->route('otp.verify', ['email' => $doctor->email]);
            }

            // Redirect to documents upload if incomplete
            if (! $doctor->documents_completed) {
                return redirect()->route('upload-documents', ['email' => $doctor->email]);
            }

            // Successful login
            return redirect()->route('doctor.dashboard')
                             ->with('success', 'تم تسجيل الدخول بنجاح.');
        }

        // Increment the rate limiter for failed attempt
        RateLimiter::hit($throttleKey);

        return redirect()->back()->withErrors([
            'login' => 'البريد الإلكتروني أو رقم الهاتف أو كلمة المرور غير صحيحة.',
        ]);
    }


    public function checker()
    {
        $doctor = Doctor::where('code', request('licence'))->first();
        return view('website.checker', compact('doctor'));
    }



        public function upload_documents(Request $request)
        {
            $doctor = Doctor::where('email', $request->email)
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
            // 1) تأكد أنه ثمة ملف
            $allFiles = $request->allFiles();
            if (empty($allFiles)) {
                return response('No file uploaded', 400);
            }
            $file = array_shift($allFiles);
        
            // 2) نحدد نوع الرفع: طبيب أم منشأة؟
            if ($request->has('doctor_id')) {
                $request->validate([
                    'doctor_id'    => 'required|integer|exists:doctors,id',
                    'file_type_id' => 'required|integer|exists:file_types,id',
                ]);
                $owner       = Doctor::findOrFail($request->doctor_id);
                $ownerKey    = 'doctor_id';
                $ownerId     = $owner->id;
                $relation    = $owner->files();
                $typeQuery   = FileType::where('doctor_type', $owner->type->value);
                $completeCol = 'documents_completed';
            }
            elseif ($request->has('medical_facility_id')) {
                $request->validate([
                    'medical_facility_id' => 'required|integer|exists:medical_facilities,id',
                    'file_type_id'        => 'required|integer|exists:file_types,id',
                ]);
                $owner       = MedicalFacility::findOrFail($request->medical_facility_id);
                $ownerKey    = 'medical_facility_id';
                $ownerId     = $owner->id;
                $relation    = $owner->files();
                $medical_facility_type = $owner->type == "private_clinic" ? "single" : "services";
                $typeQuery   = FileType::where('type', 'medical_facility')
                                       ->where('facility_type',$medical_facility_type);
                $completeCol = 'membership_status';
            }
            else {
                return response('Missing owner identifier', 400);
            }
        
            $fileTypeId = $request->file_type_id;
            
            // 3) خزّن الملف
            $path = $file->store(
                ($request->has('doctor_id') ? "doctors/{$ownerId}" : "facilities/{$ownerId}"),
                'public'
            );
        
            $fileType = FileType::findOrFail($fileTypeId);
            
            $fileData = [
                $ownerKey      => $ownerId,
                'file_type_id' => $fileTypeId,
                'file_name'    => $fileType->name,
                'file_path'    => $path,
                'order_number' => $fileType->order_number,
            ];
        
            if ($request->has('medical_facility_id') || 
                ($request->has('doctor_id') && $owner->type->value == "visitor")) {
                $fileData['renew_number'] = $owner->renew_number;
            }
        
            $relation->create($fileData);
        
            $is_for_registeration = $owner->membership_status->value == "expired" ? 0 : 1;
        
            $requiredTypeIds = $typeQuery
                ->where('for_registration', $is_for_registeration)
                ->where('is_required',    true)
                ->pluck('id')
                ->toArray();
        
            // للأطباء الزوار: التحقق من الملفات بنفس renew_number
            if ($request->has('doctor_id') && $owner->type->value == "visitor") {
                $uploadedCount = $relation
                    ->whereIn('file_type_id', $requiredTypeIds)
                    ->where('renew_number', $owner->renew_number)
                    ->distinct()
                    ->count('file_type_id');
            } else {
                // للأطباء العاديين والمنشآت
                $uploadedCount = $relation
                    ->whereIn('file_type_id', $requiredTypeIds)
                    ->distinct()
                    ->count('file_type_id');
            }
        
            // 6) إذا اكتمل الرفع، حدّث العمود المناسب
            if ($uploadedCount === count($requiredTypeIds)) {
                if ($request->has('doctor_id')) {
                    $owner->$completeCol = true;
                    $owner->registered_at = now();
                } else {
                    // المنشأة الطبية: بعد الرفع الكامل نضعها قيد المراجعة مثلاً
                    if($owner->membership_status->value == "under_complete") {
                        $owner->$completeCol = 'under_approve';
                    }
        
                    if($owner->membership_status->value == "expired") {
                        $owner->$completeCol = 'under_renew';
                    }
                }
                $owner->save();
            }
        
            return response($path, 200);
        }
    


        public function filepond_revert(Request $request)
        {
            // الحصول على مسار الملف من الطلب
            $filePath = $request->getContent();
            
            if (empty($filePath)) {
                return response('No file path provided', 400);
            }
            
            try {
                // البحث عن الملف في قاعدة البيانات
                $file = null;
                
                // البحث في ملفات الأطباء
                $doctorFile = DoctorFile::where('file_path', $filePath)
                    ->whereNotNull('doctor_id')
                    ->first();
                    
                // البحث في ملفات المنشآت الطبية
                $facilityFile = MedicalFacilityFile::where('file_path', $filePath)
                    ->whereNotNull('medical_facility_id')
                    ->first();
                    
                $file = $doctorFile ?? $facilityFile;
                
                if (!$file) {
                    return response('File not found in database', 404);
                }
                
                // تحديد المالك (طبيب أو منشأة)
                if ($file->doctor_id) {
                    $owner = Doctor::find($file->doctor_id);
                    $ownerKey = 'doctor_id';
                    $completeCol = 'documents_completed';
                    $typeQuery = FileType::where('doctor_type', $owner->type->value);
                } else {
                    $owner = MedicalFacility::find($file->medical_facility_id);
                    $ownerKey = 'medical_facility_id';
                    $completeCol = 'membership_status';
                    $medical_facility_type = $owner->type == "private_clinic" ? "single" : "services";
                    $typeQuery = FileType::where('type', 'medical_facility')
                                         ->where('facility_type', $medical_facility_type);
                }
                
                if (!$owner) {
                    return response('Owner not found', 404);
                }
                
                // حذف الملف من التخزين
                if (Storage::disk('public')->exists($filePath)) {
                    Storage::disk('public')->delete($filePath);
                }
                
                // حذف السجل من قاعدة البيانات
                $file->delete();
                
                // إعادة حساب حالة اكتمال المستندات
                $is_for_registration = $owner->membership_status->value == "expired" ? 0 : 1;
                
                $requiredTypeIds = $typeQuery
                    ->where('for_registration', $is_for_registration)
                    ->where('is_required', true)
                    ->pluck('id')
                    ->toArray();
                
                $uploadedCount = $owner->files()
                    ->whereIn('file_type_id', $requiredTypeIds)
                    ->distinct()
                    ->count('file_type_id');
                
                // تحديث حالة اكتمال المستندات
                if ($uploadedCount < count($requiredTypeIds)) {
                    if ($file->doctor_id) {
                        // للطبيب: إذا لم تكتمل المستندات
                        $owner->$completeCol = false;
                    } else {
                        // للمنشأة الطبية: إرجاع الحالة إلى قيد الإكمال
                        if ($owner->membership_status->value == 'under_approve') {
                            $owner->$completeCol = 'under_complete';
                        } elseif ($owner->membership_status->value == 'under_renew') {
                            $owner->$completeCol = 'expired';
                        }
                    }
                    $owner->save();
                }
                
                return response('File deleted successfully', 200);
                
            } catch (\Exception $e) {
                \Log::error('FilePond revert error: ' . $e->getMessage());
                return response('Error deleting file: ' . $e->getMessage(), 500);
            }
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
