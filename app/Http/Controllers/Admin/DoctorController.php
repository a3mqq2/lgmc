<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\Country;
use App\Models\Capacity;
use App\Models\Specialty;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log as laravelLog;
use App\Http\Controllers\Controller;
use PhpParser\Comment\Doc;
use App\Models\Log;

class DoctorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Doctor::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('phone')) {
            $query->where('phone', 'like', '%' . $request->input('phone') . '%');
        }

        if ($request->has('email')) {
            $query->where('email', 'like', '%' . $request->input('email') . '%');
        }


        if ($request->has('academic_degree')) {
            $query->where('academic_degree_id', $request->input('academic_degree'));
        }

        if ($request->has('date_from') && $request->has('date_to')) {
            $query->whereBetween('created_at', [$request->input('date_from'), $request->input('date_to')]);
        }

        if(get_area_name() == "user") {
            $query->where("branch_id", auth()->user()->branch_id);
        }
        
        $doctors = $query->paginate(10);
        $academicDegrees = AcademicDegree::all();

        return view('general.doctors.index', compact('doctors', 'academicDegrees'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $universities = University::orderByDesc('id')->get();
        $medicalFacilities = MedicalFacility::when(get_area_name() != "admin", function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->orderByDesc('id')->get();
        $countries = Country::orderByDesc('id')->get();
        $academicDegrees = AcademicDegree::all();
        $capacities = Capacity::all();
        $specialties = Specialty::whereNull('specialty_id')->orderByDesc('id')->get();
        return view('general.doctors.create', compact('universities', 'medicalFacilities','countries','academicDegrees','capacities','specialties'));
    }

    /**
     * Store a newly created resource in storage.
     */
    
    public function store(Request $request)
    {

        $request->validate([
            'name' => 'required|string',
            'name_en' => 'required|string',
            'national_number' => 'required|string',
            'mother_name' => 'required|string',
            'country_id' => 'required|numeric',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string',
            'gender' => 'required|string',
            'passport_number' => 'required|string',
            'passport_expiration' => 'required|date',
            'phone' => 'required|string',
            'phone_2' => 'nullable|string',
            'address' => 'required|string',
            'email' => 'required|email',
            'hand_graduation_id' => 'required|numeric',
            'internership_complete' => 'required|date',
            'academic_degree_id' => 'required|numeric',
            'capacity_id' => 'required|numeric',
            'medical_facilities' => 'nullable|array',
            'specialty_1_id' => 'required|numeric',
            'specialty_2_id' => 'nullable|numeric',
            'specialty_3_id' => 'nullable|numeric',
            'ex_medical_facilities' => 'nullable|string',
            'experience' => 'required|string',
            'notes' => 'nullable|string',
        ]);


        try {
            DB::beginTransaction();
            $doctor = new Doctor;
            $doctor->code = "-";
            $doctor->name = $request->name;
            $doctor->name_en = $request->name_en;
            $doctor->national_number = $request->national_number;
            $doctor->mother_name = $request->mother_name;
            $doctor->country_id = $request->country_id;
            $doctor->date_of_birth = $request->date_of_birth;
            $doctor->marital_status = $request->marital_status;
            $doctor->gender = $request->gender;
            $doctor->passport_number = $request->passport_number;
            $doctor->passport_expiration = $request->passport_expiration;
            $doctor->phone = $request->phone;
            $doctor->phone_2 = $request->phone_2;
            $doctor->address = $request->address;
            $doctor->email = $request->email;
            $doctor->hand_graduation_id = $request->hand_graduation_id;
            $doctor->internership_complete = $request->internership_complete;
            $doctor->academic_degree_id = $request->academic_degree_id;
            $doctor->qualification_date = $request->qualification_date;
            $doctor->qualification_university_id = $request->qualification_university_id;
            $doctor->capacity_id = $request->capacity_id;
            $doctor->specialty_1_id = $request->specialty_1_id;
            $doctor->specialty_2_id = $request->specialty_2_id;
            $doctor->specialty_3_id = $request->specialty_3_id;
            $doctor->ex_medical_facilities = $request->ex_medical_facilities;
            $doctor->experience = $request->experience;
            $doctor->notes = $request->notes;
            $doctor->branch_id = $request->branch_id ? $request->branch_id : auth()->user()->branch_id;
            $doctor->save();
            $doctor->medicalFacilities()->attach($request->medical_facilities);
            $doctor->save();


            $doctor->code = $doctor->branch->code . '-' . $doctor->id;

            $doctor->save();
            Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء طبيب جديد: " . $doctor->name]);
            DB::commit();
            return redirect()->route(($request->branch_id ? 'admin' : 'user') . '.doctors.index')->with('success', 'تم اضافة الطبيب بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            laravelLog::error($e->getMessage());
            return redirect()->back()->withInput()->withErrors(["  حدث خطا ما يرجى الاتصال بالدعم الفني " . $e->getMessage()]);
        }
    }
    
    /**
     * Display the specified resource.
     */
    public function show(Doctor $doctor)
    {
        return view('general.doctors.show', compact('doctor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Doctor $doctor)
    {
        $universities = University::orderByDesc('id')->get();
        $medicalFacilities = MedicalFacility::where('branch_id', auth()->user()->branch_id)->orderByDesc('id')->get();
        $countries = Country::orderByDesc('id')->get();
        $academicDegrees = AcademicDegree::all();
        $capacities = Capacity::all();
        $specialties = Specialty::whereNull('specialty_id')->orderByDesc('id')->get();
        return view('general.doctors.edit', compact('doctor','universities', 'medicalFacilities','countries','academicDegrees','capacities','specialties'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'name_en' => 'required|string',
            'national_number' => 'required|string',
            'mother_name' => 'required|string',
            'country_id' => 'required|numeric',
            'date_of_birth' => 'required|date',
            'marital_status' => 'required|string',
            'gender' => 'required|string',
            'passport_number' => 'required|string',
            'passport_expiration' => 'required|date',
            'phone' => 'nullable|string|digits:10',
            'phone2' => 'nullable|string|digits:10',
            'address' => 'required|string',
            'email' => 'required|email',
            'hand_graduation_id' => 'required|numeric',
            'internership_complete' => 'required|date',
            'academic_degree_id' => 'required|numeric',
            'capacity_id' => 'required|numeric',
            'medical_facilities' => 'nullable|array',
            'specialty_1_id' => 'required|numeric',
            'specialty_2_id' => 'nullable|numeric',
            'specialty_3_id' => 'nullable|numeric',
            'ex_medical_facilities' => 'nullable|string',
            'experience' => 'required|string',
            'notes' => 'nullable|string',
        ]);
    
        try {
            DB::beginTransaction();
            
            $doctor = Doctor::findOrFail($id);
            $doctor->name = $request->name;
            $doctor->name_en = $request->name_en;
            $doctor->national_number = $request->national_number;
            $doctor->mother_name = $request->mother_name;
            $doctor->country_id = $request->country_id;
            $doctor->date_of_birth = $request->date_of_birth;
            $doctor->marital_status = $request->marital_status;
            $doctor->gender = $request->gender;
            $doctor->passport_number = $request->passport_number;
            $doctor->passport_expiration = $request->passport_expiration;
            $doctor->phone = $request->phone;
            $doctor->phone_2 = $request->phone_2;
            $doctor->address = $request->address;
            $doctor->email = $request->email;
            $doctor->hand_graduation_id = $request->hand_graduation_id;
            $doctor->internership_complete = $request->internership_complete;
            $doctor->academic_degree_id = $request->academic_degree_id;
            $doctor->qualification_date = $request->qualification_date;
            $doctor->qualification_university_id = $request->qualification_university_id;
            $doctor->capacity_id = $request->capacity_id;
            $doctor->specialty_1_id = $request->specialty_1_id;
            $doctor->specialty_2_id = $request->specialty_2_id;
            $doctor->specialty_3_id = $request->specialty_3_id;
            $doctor->ex_medical_facilities = $request->ex_medical_facilities;
            $doctor->experience = $request->experience;
            $doctor->notes = $request->notes;
            $doctor->branch_id = auth()->user()->branch_id;
    
            if ($request->hasFile('certificate_of_excellence')) {
                $file = $request->file('certificate_of_excellence');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->certificate_of_excellence = $filePath;
            }
    
            if ($request->hasFile('graduationـcertificate')) {
                $file = $request->file('graduationـcertificate');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->graduationـcertificate = $filePath;
            }
    
            if ($request->hasFile('passport')) {
                $file = $request->file('passport');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->passport = $filePath;
            }
    
            if ($request->hasFile('id_card')) {
                $file = $request->file('id_card');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->id_card = $filePath;
            }
    
            if ($request->hasFile('employeer_message')) {
                $file = $request->file('employeer_message');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->employeer_message = $filePath;
            }
    
            if ($request->hasFile('id_number')) {
                $file = $request->file('id_number');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->id_number = $filePath;
            }
    
            if ($request->hasFile('birthـcertificate')) {
                $file = $request->file('birthـcertificate');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->birthـcertificate = $filePath;
            }
    
            if ($request->hasFile('personal_photo')) {
                $file = $request->file('personal_photo');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->personal_photo = $filePath;
            }
    
            if ($request->hasFile('work_visa')) {
                $file = $request->file('work_visa');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->work_visa = $filePath;
            }
    
            if ($request->hasFile('jobـcontract')) {
                $file = $request->file('jobـcontract');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->jobـcontract = $filePath;
            }
    
            if ($request->hasFile('anotherـcertificate')) {
                $file = $request->file('anotherـcertificate');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('files', $fileName);
                $doctor->anotherـcertificate = $filePath;
            }
    
            $doctor->medicalFacilities()->sync($request->medical_facilities);
            $doctor->code = auth()->user()->branch->code . '-' . $doctor->id;
            $doctor->save();
    
            Log::create(['user_id' => auth()->user()->id, 'details' => "تم تحديث معلومات الطبيب: " . $doctor->name]);
            DB::commit();
            return redirect()->route(get_area_name() . '.doctors.index')->with('success', 'تم تحديث بيانات الطبيب بنجاح');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return redirect()->back()->withInput()->withErrors(["حدث خطأ ما يرجى الاتصال بالدعم الفني " . $e->getMessage()]);
        }
    }
    

    public function print(Doctor $doctor) {
        return view("general.doctors.print", compact('doctor'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Doctor $doctor)
    {
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف الطبيب: " . $doctor->name]);
        $doctor->delete();
        return redirect()->back()->with('success', 'تم حذف الطبيب بنجاح');
    }
}
