<?php

namespace App\Http\Controllers\Common;
use App\Models\Branch;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\FileType;
use App\Mail\ApprovalEmail;
use App\Mail\RejectionEmail;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityFile;
use App\Models\MedicalFacilityType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImportMedicalFacilities;
use App\Services\MedicalFacilityService;
use App\Http\Requests\StoreMedicalFacilityRequest;

class MedicalFacilityController extends Controller
{
    protected $medicalFacilityService;

    public function __construct(MedicalFacilityService $medicalFacilityService)
    {
        $this->medicalFacilityService = $medicalFacilityService;
    }

    public function index(Request $request)
    {
        // Retrieve filtered/paginated results from the service
        $medicalFacilities = $this->medicalFacilityService->getAll(
            $request->only(['q', 'ownership', 'medical_facility_type_id']), 
            10
        );

        $medicalFacilityTypes = MedicalFacilityType::all();

        return view('general.medical-facilities.index', compact('medicalFacilities', 'medicalFacilityTypes'));
    }

    public function create()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 1)->get();
        $doctors = auth()->user()->branch ? auth()->user()->branch->doctors()->whereHas('licenses', function($q) {
            $q->where('status', 'active');
        }) : Doctor::all();
        $branches = Branch::all();
        return view('general.medical-facilities.create', compact('medicalFacilityTypes','file_types','doctors','branches'));
    }

    public function store(StoreMedicalFacilityRequest $request)
    {
        // Validation is handled by StoreMedicalFacilityRequest
        $this->medicalFacilityService->create($request->validated());

        
        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم إنشاء منشأة طبية جديدة بنجاح.');
    }

    public function show(MedicalFacility $medicalFacility)
    {
        return view('general.medical-facilities.show', compact('medicalFacility'));
    }

    public function edit($id)
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 1)->get();
        $doctors = auth()->user()->branch ? auth()->user()->branch->doctors()->whereHas('licenses', function($q) {
            $q->where('status', 'active');
        }) : Doctor::all();
        $branches = Branch::all();
        $medicalFacility = MedicalFacility::findOrFail($id);
        return view('general.medical-facilities.edit', compact('medicalFacility','medicalFacilityTypes','file_types','doctors','branches'));
    }

    public function update(Request $request, MedicalFacility $medicalFacility)
    {
        $request->validate([
            'serial_number' => "required",
            'name' => 'required|string|max:255',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            "commerical_number" => "required",
            'activity_start_date' => "required",
        ]);

        $this->medicalFacilityService->update($medicalFacility, $request->all());

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم تحديث بيانات منشأة طبية بنجاح.');
    }

    public function destroy(MedicalFacility $medicalFacility)
    {
        $this->medicalFacilityService->delete($medicalFacility);

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم حذف منشأة طبية بنجاح.');
    }

    public function import()
    {
        return view('general.medical-facilities.import');
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);


        Excel::import(new ImportMedicalFacilities, $request->file('file'));

        
        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم استيراد المنشآت الطبية بنجاح.');
    }

    public function file_destroy($fileId)
    {
        $file = MedicalFacilityFile::findOrFail($fileId);
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح.');
    }




    public function approve(MedicalFacility $facility)
    {
        // تحديث الحالة
        $facility->update(['membership_status'=>'active']);

    
        // إرسال إيميل القبول
        Mail::to($facility->manager->email)
            ->queue(new ApprovalEmail($facility->manager));
    

        $facility->makeCode();
        $facility->save();

        return back()->with('success','تم قبول المنشأة بنجاح.');
    }
    
public function reject(Request $request, MedicalFacility $facility)
{
    $request->validate(['reason'=>'required|string']);
    $facility->update([
        'membership_status'=>'rejected',
        'reason' =>$request->reason,
    ]);

    Mail::to($facility->manager->email)
        ->queue(new RejectionEmail($facility->manager, $request->reason));

    return back()->with('success','تم رفض المنشأة بنجاح.');
}
}
