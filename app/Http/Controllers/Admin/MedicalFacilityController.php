<?php

namespace App\Http\Controllers\Admin;

use App\Models\Log;
use App\Models\MedicalFacility;
use App\Http\Controllers\Controller;
use App\Models\Branch;
use App\Models\MedicalFacilityType;
use Illuminate\Http\Request;

class MedicalFacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicalFacility::query();

        // Filtering
        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->input('name') . '%');
        }

        if ($request->has('ownership')) {
            $query->where('ownership', $request->input('ownership'));
        }

        if ($request->has('medical_facility_type_id')) {
            $query->where('medical_facility_type_id', $request->input('medical_facility_type_id'));
        }


        if(get_area_name() == "user") {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        $medicalFacilities = $query->paginate(10);
        $medicalFacilityTypes = MedicalFacilityType::all();
        return view('general.medical-facilities.index', compact('medicalFacilities','medicalFacilityTypes'));
    }

    public function create()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $branches = Branch::all();
        return view('general.medical-facilities.create', compact('medicalFacilityTypes','branches'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'ownership' => 'required|in:private,public',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'nullable|string|digits:10',
            'branch_id' => 'nullable',
            "commerical_number" => "required",
        ]);
    
        $validatedData['user_id'] = auth()->user()->id;

        if(!$request->branch_id) {
            $validatedData['branch_id'] = auth()->user()->branch_id;
        }

        $medicalFacility = MedicalFacility::create($validatedData);

        // Log creation with Arabic message and medical facility name
        Log::create(['user_id' => auth()->user()->id, 'details' => 'تم إنشاء منشأة طبية جديدة: ' . $medicalFacility->name]);

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم إنشاء منشأة طبية جديدة بنجاح.');
    }

    public function show(MedicalFacility $medicalFacility)
    {
        return view('general.medical-facilities.show', compact('medicalFacility'));
    }

    public function edit(MedicalFacility $medicalFacility)
    {
        if(get_area_name() == "user" && $medicalFacility->branch_id != $medicalFacility->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك التعديل على هذه المؤسسة']);
        }

        $branches = Branch::all();
        $medicalFacilityTypes = MedicalFacilityType::all();
        return view('general.medical-facilities.edit', compact('medicalFacility', 'branches','medicalFacilityTypes'));
    }

    public function update(Request $request, MedicalFacility $medicalFacility)
    {
        if(get_area_name() == "user" && $medicalFacility->branch_id != $medicalFacility->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك التعديل على هذه المؤسسة']);
        }


        $request->validate([
            'name' => 'required|string|max:255',
            'ownership' => 'required|in:private,public',
            'medical_facility_type_id' => 'required|exists:medical_facility_types,id',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'branch_id' => "required",
            "commerical_number" => "required",
        ]);

        $medicalFacility->update($request->all());

        // Log update with Arabic message and medical facility name
        Log::create(['user_id' => auth()->user()->id, 'details' => 'تم تحديث بيانات منشأة طبية: ' . $medicalFacility->name]);

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم تحديث بيانات منشأة طبية بنجاح.');
    }

    public function destroy(MedicalFacility $medicalFacility)
    {

        if(get_area_name() == "user" && $medicalFacility->branch_id != $medicalFacility->branch_id) {
            return redirect()->back()->withErrors(['لا يمكنك التعديل على هذه المؤسسة']);
        }

        
        $medicalFacility->delete();

        // Log deletion with Arabic message and medical facility name
        Log::create(['user_id' => auth()->user()->id, 'details' => 'تم حذف منشأة طبية: ' . $medicalFacility->name]);

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم حذف منشأة طبية بنجاح.');
    }
}
