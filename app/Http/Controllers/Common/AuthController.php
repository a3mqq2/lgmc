<?php

namespace App\Http\Controllers\Common;
use Carbon\Carbon;

use App\Models\Otp;
use App\Models\Branch;
use App\Models\Doctor;
use App\Mail\VerifyOtp;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\FileType;
use App\Models\Blacklist;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Event;
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

            if(!$user->active)
            {
                Auth::logout();
                return redirect()->back()->withErrors([
                    'email' => 'حسابك معطل يرجى التواصل مع الاداره',
                ]);
            }

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



        if(request('area'))
        {
            return redirect()->route('finance.home');
        } else {
            return redirect()->route('user.home');
        }

    }



    public function register(Request $request)
    {
        $countries = Country::all();
        $academicDegrees = AcademicDegree::all();
        $universities = University::all();
        $doctor_ranks = DoctorRank::where('doctor_type', $request->type)->get();
        $specialties = Specialty::whereNull('specialty_id')->get();
        $branches = Branch::all();
        $medicalFacilities = MedicalFacility::all();

        if($request->type)
        {
            if($request->type == "foreign")
            {
                return view('doctor.register_foreign', compact('countries','academicDegrees','universities','doctor_ranks','specialties','branches','medicalFacilities'));
            }

            if($request->type == "visitor")
            {
                return view('doctor.register_visitor', compact('countries','academicDegrees','universities','doctor_ranks','specialties','branches','medicalFacilities'));
            }


            if($request->type == "palestinian")
            {
                return view('doctor.register_palestinian', compact('countries','academicDegrees','universities','doctor_ranks','specialties','branches','medicalFacilities'));
            }


        }


        return view('doctor.register', compact('countries','academicDegrees','universities','doctor_ranks','specialties','branches','medicalFacilities'));
    }



    public function register_store(StoreDoctorRequest $request)
    {



        $data = $request->validated();

            // استخراج نوع الطبيب من البيانات
            $doctorType = $data['type'];

          
             // check the doctor is exists before
            $doctor = Doctor::where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->where('email', $data['email'])
            ->where('type', $data['type'])
            ->first();

            if($doctor)
            {
                return redirect()->back()->withInput()->withErrors(["لقد تم تسجيلك مسبقاََ يرجى تسجيل الدخول"]);
            }
            

            DB::beginTransaction();

            try {
                
                $data['password'] = Hash::make($data['password']);


                $medicalFacilities = [];    
                if(isset($data['ex_medical_facilities']))
                {
                    $medicalFacilities = $data['ex_medical_facilities'];
                    $medicalFacilities = array_filter($medicalFacilities, function($value) {
                        return $value != '-';
                    });
                    unset($data['ex_medical_facilities']);
                }


                if(isset($data['branch_id']))
                {
                    $data['branch_id'] = $data['branch_id'];
                }

             
                    $doctor = Doctor::create($data);

                    $doctor->institutions()->attach($medicalFacilities ?? []);

                    $doctor->membership_status = 'pending';
                    $doctor->membership_expiration_date = null;
                    $doctor->code = null;
                    $doctor->index = null;
                    $doctor->save();

                $file_types = FileType::where('type', 'doctor')
                    ->where('doctor_type', $data['type'])
                    ->where('for_registration', 1)
                    ->get();

                // foreach ($file_types as $file_type) {
                //     if ($file_type->is_required && empty($data['documents'][$file_type->id])) {
                //         return redirect()->back()->withInput()->withErrors(["الملف {$file_type->name} مطلوب ولم يتم تحميله."]);
                //     }
                // }

                // foreach ($file_types as $file_type) {
                //     if (isset($data['documents'][$file_type->id])) {
                //         $file = $data['documents'][$file_type->id];
                //         $path = $file->store('doctors');
                //         $doctor->files()->create([
                //             'file_name' => $file->getClientOriginalName(),
                //             'file_type_id' => $file_type->id,
                //             'file_path' => $path,
                //         ]);
                //     }
                // }

                // إنشاء الفاتورة الخاصة بالطبيب
                // $this->createInvoice($doctor);


                DB::commit();

                $this->sendEmailVerificationNotification($doctor);
                Auth::guard('doctor')->login($doctor);
                return redirect()->route('otp.verify', ['email' => $doctor->email]);
            } catch (\Exception $e) {
                DB::rollback();
                throw $e; // إعادة إلقاء الاستثناء بعد التراجع عن العملية
            }
    }

    public function sendEmailVerificationNotification($doctor)
    {
        // Generate a 6-digit OTP
        $otpCode = rand(100000, 999999);
    
        // Save OTP to the database
        Otp::create([
            'email' => $doctor->email,
            'otp_code' => $otpCode,
            'is_verified' => false,
            'expires_at' => Carbon::now()->addMinutes(30), // OTP expires in 10 minutes
        ]);
    
        // Send OTP via email
        try {
            Mail::to($doctor->email)
            ->send(new VerifyOtp($otpCode, $doctor->email));
        } catch(\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage());
        }
    }
}
