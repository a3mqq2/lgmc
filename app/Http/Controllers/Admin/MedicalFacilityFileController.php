<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalFacilityType;
use App\Models\Log;
use Illuminate\Http\Request;

class MedicalFacilityTypeController extends Controller
{
    /**
     * Display a listing of the medical facility types.
     */
    public function index()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        return view('admin.medical_facility_types.index', compact('medicalFacilityTypes'));
    }

    /**
     * Show the form for creating a new medical facility type.
     */
    public function create()
    {
        return view('admin.medical_facility_types.create');
    }

    /**
     * Store a newly created medical facility type in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        $medicalFacilityType = MedicalFacilityType::create($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم إنشاء نوع مرفق طبي جديد: {$medicalFacilityType->name}",
            'loggable_id' => $medicalFacilityType->id,
            'loggable_type' => MedicalFacilityType::class,
            'action' => 'create_medical_facility_type',
        ]);

        return redirect()->route(get_area_name() . '.medical-facility-types.index')
            ->with('success', 'تم إنشاء نوع مرفق طبي جديد بنجاح.');
    }

    /**
     * Display the specified medical facility type.
     */
    public function show(MedicalFacilityType $medicalFacilityType)
    {
        return view('admin.medical_facility_types.show', compact('medicalFacilityType'));
    }

    /**
     * Show the form for editing the specified medical facility type.
     */
    public function edit(MedicalFacilityType $medicalFacilityType)
    {
        return view('admin.medical_facility_types.edit', compact('medicalFacilityType'));
    }

    /**
     * Update the specified medical facility type in storage.
     */
    public function update(Request $request, MedicalFacilityType $medicalFacilityType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        $medicalFacilityType->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات نوع مرفق طبي: {$medicalFacilityType->name}",
            'loggable_id' => $medicalFacilityType->id,
            'loggable_type' => MedicalFacilityType::class,
            'action' => 'update_medical_facility_type',
        ]);

        return redirect()->route(get_area_name() . '.medical-facility-types.index')
            ->with('success', 'تم تعديل بيانات نوع مرفق طبي بنجاح.');
    }

    /**
     * Remove the specified medical facility type from storage.
     */
    public function destroy(MedicalFacilityType $medicalFacilityType)
    {
        $name = $medicalFacilityType->name;
        $medicalFacilityType->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف نوع مرفق طبي: {$name}",
            'loggable_id' => $medicalFacilityType->id,
            'loggable_type' => MedicalFacilityType::class,
            'action' => 'delete_medical_facility_type',
        ]);

        return redirect()->route(get_area_name() . '.medical-facility-types.index')
            ->with('success', 'تم حذف نوع مرفق طبي بنجاح.');
    }
}
