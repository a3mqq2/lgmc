<?php

namespace App\Http\Controllers\Common;
use App\Models\Log;

use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\FileType;
use App\Models\Blacklist;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\AcademicDegree;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Http\Requests\StoreDoctorRequest;

class AuthController extends Controller
{
    public function login() {
        return view('login');
    }

    public function do_login(Request $request) {
        $request->validate([
            "email" => "required",
            "password" => "required",
            "remember_me" => "nullable",
        ]);
    
        $credentials = $request->only('email', 'password');
    
        if(Auth::attempt($credentials)) {
            $user = auth()->user();
            $access_token = $user->createToken('authToken')->plainTextToken;
            Cookie::queue('ast', $access_token, 777500);
            return redirect('/sections');
        } else {
            return redirect()->back()->withErrors([
                'email' => 'يرجى التأكد من بيانات الحساب'
            ]);
        }
    }
    


    public function logout(Request $request) {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }



    public function sections() {
        return view('sections');
    }


    public function change_branch(Request $request)
    {
        $user = auth()->user();
        if(in_array($request->branch_id, auth()->user()->branches->pluck('id')->toArray())) {
            $user->branch_id = $request->branch_id;
        } else {
            if($request->branch_id == null && auth()->user()->roles->where('name', 'general_admin')->count() > 0) {
                $user->branch_id = null;
                $user->save();
            } else {
                return redirect()->back()->withErrors(['ليس لديك صلاحية للوصول إلى هذا الفرع']);
            }
        }
        $user->branch_id = $request->branch_id;
        $user->save();
        return redirect()->back();
    }



    public function register()
    {
        $countries = Country::all();
        $academicDegrees = AcademicDegree::all();
        $universities = University::all();
        $file_types = FileType::where('type', 'doctor')->where('doctor_type', request('type'))->get();
        $doctor_ranks = DoctorRank::all();
        $specialties = Specialty::whereNull('specialty_id')->get();
        $branches = Branch::all();
        return view('doctor.register', compact('countries','academicDegrees','universities','file_types','doctor_ranks','specialties','branches'));
    }



    public function register_store(StoreDoctorRequest $request)
    {



        $data = $request->validated();

            // استخراج نوع الطبيب من البيانات
            $doctorType = $data['type'];

            // بناء استعلام التحقق من البلاك ليست بناءً على نوع الطبيب
            $blacklistQuery = Blacklist::where('doctor_type', $doctorType)
                ->where(function ($query) use ($data, $doctorType) {
                    $query->where('name', $data['name'])
                        ->orWhere('number_phone', $data['phone']);
                    
                    if ($doctorType === 'libyan') {
                        // تحقق من رقم الهوية الوطنية لليبيا
                        $query->orWhere('id_number', $data['national_number']);
                    } else {
                        // تحقق من رقم الجواز للأطباء غير الليبيين
                        $query->orWhere('passport_number', $data['passport_number']);
                    }
                });

            // إذا كان الطبيب موجودًا في البلاك ليست، إلقاء استثناء
            if ($blacklistQuery->exists()) {
                return redirect()->back()->withInput()->withErrors(['هذا الطبيب موجود في البلاك ليست ولا يمكن إضافته.']);
            }

            DB::beginTransaction();

            try {
                // توليد رمز الطبيب
                $data['code'] = ($request->branch_id ? $data['branch_id'] : auth()->user()->branch_id) . '-' . (Doctor::count() + 1);
                
                // إنشاء السجل الجديد للطبيب
                $data['password'] = Hash::make($data['password']);

                // 

                $data['email'] = strtolower(str_replace(' ', '.', $data['name_en'])) . env('EMAIL_HOST');
                $data['branch_id'] = $data['branch_id'] ?? auth()->user()->branch_id;
                $doctor = Doctor::create($data);

                // ربط المرافق الطبية
                $doctor->medicalFacilities()->attach($data['medical_facilities'] ?? []);

                // تحديث رمز الطبيب بناءً على الفرع
                $doctor->code = $doctor->branch->code . '-' . Doctor::count()+1;
                $doctor->membership_status = 'pending';
                $doctor->membership_expiration_date = null;
                $doctor->save();

                // جلب أنواع الملفات للأطباء
                $file_types = FileType::where('type', 'doctor')
                    ->where('doctor_type', $data['type'])
                    ->get();

                // التحقق من وجود الملفات المطلوبة
                foreach ($file_types as $file_type) {
                    if ($file_type->is_required && empty($data['documents'][$file_type->id])) {
                        return redirect()->back()->withInput()->withErrors(["الملف {$file_type->name} مطلوب ولم يتم تحميله."]);
                    }
                }

                // معالجة ملفات المستندات
                foreach ($file_types as $file_type) {
                    if (isset($data['documents'][$file_type->id])) {
                        $file = $data['documents'][$file_type->id];
                        $path = $file->store('doctors');
                        $doctor->files()->create([
                            'file_name' => $file->getClientOriginalName(),
                            'file_type_id' => $file_type->id,
                            'file_path' => $path,
                        ]);
                    }
                }

                // إنشاء الفاتورة الخاصة بالطبيب
                // $this->createInvoice($doctor);


                DB::commit();

                return redirect()->route('doctor-login')->with('success', 'تم تسجيل عضويتك بنجاح يرجى التوجه للفرع الذي قمت بالتسجيل به لإكمال الاجراءات نشكرك على التسجيل ');
            } catch (\Exception $e) {
                DB::rollback();
                throw $e; // إعادة إلقاء الاستثناء بعد التراجع عن العملية
            }
    }
}
