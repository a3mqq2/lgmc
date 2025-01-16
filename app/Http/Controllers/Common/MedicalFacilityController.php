<?php

namespace App\Http\Controllers\Common;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityType;
use App\Http\Requests\StoreMedicalFacilityRequest;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\FileType;
use App\Services\MedicalFacilityService;

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
            $request->only(['name', 'ownership', 'medical_facility_type_id']), 
            10
        );

        $medicalFacilityTypes = MedicalFacilityType::all();

        return view('general.medical-facilities.index', compact('medicalFacilities', 'medicalFacilityTypes'));
    }

    public function create()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $file_types = FileType::where('type', 'medical_facility')->get();
        $doctors = auth()->user()->branch ? auth()->user()->branch->doctors : Doctor::all();
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

    public function edit(MedicalFacility $medicalFacility)
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        return view('general.medical-facilities.edit', compact('medicalFacility','medicalFacilityTypes'));
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
}
