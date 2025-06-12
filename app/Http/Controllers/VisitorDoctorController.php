<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\FileType;
use App\Models\Specialty;
use App\Models\DoctorFile;
use App\Models\DoctorRank;
use App\Models\University;
use Illuminate\Http\Request;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\Constraint\Count;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;

class VisitorDoctorController extends Controller
{
    /**
     * عرض نموذج التسجيل
     */
    public function create()
    {
        $countries = Country::orderBy('nationality_name_ar')->get();
        $universities = University::orderBy('name')->get();
        $doctor_ranks = DoctorRank::where('doctor_type', 'visitor')->orderBy('id')->get();
        $specialties = Specialty::orderBy('name')->get();
        $medicalFacilities = MedicalFacility::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        return view('doctor.visitor-doctors.create', compact(
            'countries',
            'universities',
            'doctor_ranks',
            'specialties',
            'medicalFacilities',
            'branches'
        ));
    }

    /**
     * تخزين بيانات الطبيب الزائر
     */
    public function register_store(StoreDoctorRequest $request)
    {
        $data = $request->validated();

        if(isset($data['date_of_birth']))
        {
            $data['date_of_birth'] = date('Y-m-d', strtotime($data['date_of_birth']));
        }

        // استخراج نوع الطبيب من البيانات
        $doctorType = $data['type'];


        $doctor = Doctor::where('name', $data['name'])
            ->where('email', $data['email'])
            ->where('type', $data['type'])
            ->first();

        if($doctor)
        {
            return redirect()->route('doctor.my-facility', ['visitors' => 1])->withInput()->withErrors(["لقد تم تسجيلك مسبقاً يرجى تسجيل الدخول"]);
        }

        DB::beginTransaction();

        try {
            

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

            $doctor->medical_facility_id = auth('doctor')->user()->medicalFacility->id;
            $doctor->membership_status = 'under_approve';
            if($data['type'] == "visitor")
            {
                $doctor->membership_status = "under_upload";
                $doctor->branch_id = auth('doctor')->user()->branch_id;
            }
            $doctor->membership_expiration_date = null;
            $doctor->code = null;
            $doctor->index = null;
            $doctor->save();

            $file_types = FileType::where('type', 'doctor')
                ->where('doctor_type', $data['type'])
                ->where('for_registration', 1)
                ->get();

            DB::commit();

            
            return redirect()->route('doctor.visitor-doctors.upload-documents', $doctor)
                ->with('success', 'تم تسجيل الطبيب الزائر بنجاح');

        } catch (\Exception $e) {
            DB::rollback();
            throw $e; // إعادة إلقاء الاستثناء بعد التراجع عن العملية
        }
    }

    /**
     * عرض قائمة الأطباء الزائرين
     */
    public function index()
    {
        $doctors = Doctor::where('type', 'visitor')
            ->with(['country', 'medicalFacility', 'doctorRank', 'branch'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('visitor-doctors.index', compact('doctors'));
    }

    /**
     * عرض تفاصيل طبيب زائر
     */
    public function show($id)
    {
        $doctor = Doctor::where('type', 'visitor')
            ->with(['country', 'medicalFacility', 'doctorRank', 'branch', 'institutions'])
            ->findOrFail($id);


        $universities = University::orderBy('name')->get();
        $doctor_ranks = DoctorRank::where('doctor_type', 'visitor')->orderBy('id')->get();
        $specialties = Specialty::orderBy('name')->get();
        $academicDegrees = AcademicDegree::orderBy('name')->get();
        $countries = Country::all();
        return view('doctor.visitor-doctors.show', compact('doctor',
            'universities',
            'doctor_ranks',
            'specialties',
            'academicDegrees',
            'countries'
        ));
    }

    /**
     * عرض نموذج تعديل بيانات طبيب زائر
     */
    public function edit($id)
    {
        $doctor = Doctor::where('type', 'visitor')->findOrFail($id);
        
        $countries = Country::orderBy('nationality_name_ar')->get();
        $universities = University::orderBy('name')->get();
        $doctor_ranks = DoctorRank::orderBy('id')->get();
        $specialties = Specialty::orderBy('name')->get();
        $medicalFacilities = MedicalFacility::orderBy('name')->get();
        $branches = Branch::orderBy('name')->get();

        return view('visitor-doctors.edit', compact(
            'doctor',
            'countries',
            'universities',
            'doctor_ranks',
            'specialties',
            'medicalFacilities',
            'branches'
        ));
    }

    /**
     * تحديث بيانات طبيب زائر
     */
    public function update(Request $request, Doctor $doctor)
    {

        $validated = $request->validate([
            "name" => "required|string|max:255",
            "country_id" => "required|numeric",
            "visit_from" => "required|date",
            "visit_to" => "required|date|after_or_equal:visit_from",
            "phone_2" => "nullable|unique:doctors,phone_2,{$doctor->id}",
            "email" => "required|email|max:255|unique:doctors,email,{$doctor->id}",
            "hand_graduation_id" => "required|numeric",
            "graduation_date" => "required|date",
            "qualification_university_id" => "required|numeric",
            "internership_complete" => "required|date",
            "doctor_rank_id" => "required|numeric",
            "specialty_1_id" => "required|numeric",
            "specialty_2" => "nullable|string|max:255",
        ]);

        $doctor->update($validated);
    

        $doctor->membership_status = "under_approve";
        $doctor->save();

        if ($request->hasFile('reupload_files')) {
            $reuploads = $request->file('reupload_files');
    
            foreach ($reuploads as $fileTypeId => $file) {
                if (! $file) {
                    continue;
                }
    
                $path = $file->store("documents/{$doctor->id}", 'public');
                $doctorFile = DoctorFile::find($fileTypeId);
                $doctorFile->file_path = $path;
                $doctorFile->file_name = $file->getClientOriginalName();
                $doctorFile->save();
            }
        }
    
        return redirect()->
            route('doctor.my-facility', ['visitors' => 1])
            ->with('success', 'تم تحديث البيانات والمستندات بنجاح');
    }


    public function upload_documents(Doctor $doctor)
    {
        $file_types = FileType::where('doctor_type', $doctor->type->value)
            ->where('for_registration', 1)
                                ->get();


        $uploadedTypeIds = $doctor->files()
        ->where('renew_number', $doctor->renew_number)
        ->pluck('file_type_id')
        ->toArray();

        $missingFileTypes = $file_types->filter(function($ft) use ($uploadedTypeIds) {
            return ! in_array($ft->id, $uploadedTypeIds);
        });

        return view('doctor.visitor-doctors.upload-documents', [
            'doctor'            => $doctor,
            'missingFileTypes'  => $missingFileTypes,
        ]);
        
    }


    public function complete_registration(Doctor $doctor)
    {
        $doctor->registered_at = now();
        $doctor->membership_status = "under_approve";
        $doctor->save();

        return redirect()->route('doctor.my-facility', ['visitors' => 1])
        ->with('success', 'تم إكتمال التسجيل بنجاح بإنتظار موافقة النقابة العامة على طلبك');

    }
    public function uploadReport(Request $request, Doctor $visitor)
    {
        $request->validate([
            'report_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048', // 2MB max
        ], [
            'report_file.required' => 'يرجى اختيار ملف للرفع',
            'report_file.mimes' => 'نوع الملف غير مدعوم. يرجى اختيار ملف PDF أو صورة',
            'report_file.max' => 'حجم الملف يجب أن يكون أقل من 2 ميجابايت'
        ]);
    
        try {
            // Store the file
            $file = $request->file('report_file');
            $fileName = 'visitor_report_' . $visitor->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('visitor_reports', $fileName, 'public');
    
            // Create file record (assuming you have a DoctorFile model)
            $visitor->files()->create([
                'file_type_id' => 54, // Visitor report file type ID
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'uploaded_at' => now(),
                "order_number" => 10,
            ]);
    
            return response()->json([
                'success' => true,
                'message' => 'تم رفع التقرير بنجاح'
            ]);
    
        } catch (\Exception $e) {
            \Log::error('Error uploading visitor report: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => ' حدث خطأ أثناء رفع الملف. يرجى المحاولة مرة أخرى' . $e->getMessage(),
            ], 500);
        }
    }

public function renewSubscription(Request $request, Doctor $doctor)
{

    // check if any expired doctor has no file uploaded with file type id 55
    $expiredDoctors = Doctor::where('type', 'visitor')
        ->where('membership_status', 'expired')
        ->whereDoesntHave('files', function ($query) {
            $query->where('file_type_id', 54);
        })
        ->get();

        if(count($expiredDoctors) > 0)
        {
            return redirect()->back()->withErrors(['هناك طبيب زائر لم يتم رفع تقرير له']);
        }

    $request->validate([
        'visit_from' => 'required|date|after_or_equal:today',
        'visit_to' => 'required|date|after:visit_from',
    ], [
        'visit_from.required' => 'تاريخ بداية الزيارة مطلوب',
        'visit_from.after_or_equal' => 'تاريخ بداية الزيارة يجب أن يكون اليوم أو بعده',
        'visit_to.required' => 'تاريخ نهاية الزيارة مطلوب',
        'visit_to.after' => 'تاريخ نهاية الزيارة يجب أن يكون بعد تاريخ البداية',
    ]);

    try {
        // تحديث بيانات الطبيب
        $doctor->update([
            'visit_from' => $request->visit_from,
            'visit_to' => $request->visit_to,
            'membership_status' => 'under_upload',
            'renew_number' => $doctor->renew_number + 1
        ]);


        return redirect()
        ->route('doctor.visitor-doctors.upload-documents', $doctor)
        ->with('success','تم فتح طلب تجديد جديد يرجى رفع المستندات مجدداََ لأستكمال عملية التجديد');

    } catch (\Exception $e) {
        return redirect()->back()->withErrors(['حدث خطأ ما يرجى التواصل مع الدعم الفني' . $e->getMessage()]);
    }
}
}