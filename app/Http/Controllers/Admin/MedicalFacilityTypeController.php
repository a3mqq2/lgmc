<?php

namespace App\Http\Controllers\Admin;

use App\Models\MedicalFacilityType;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicalFacilityTypeController extends Controller
{
    /**
     * Display a listing of the medical facility types.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        return view('admin.medical_facility_types.index', compact('medicalFacilityTypes'));
    }

    /**
     * Show the form for creating a new medical facility type.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.medical_facility_types.create');
    }

    /**
     * Store a newly created medical facility type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        MedicalFacilityType::create($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم إنشاء نوع مرفق طبي جديد"]);

        return redirect()->route(get_area_name().'.medical-facility-types.index')
            ->with('success', 'تم إنشاء نوع مرفق طبي جديد بنجاح.');
    }

    /**
     * Display the specified medical facility type.
     *
     * @param  \App\Models\MedicalFacilityType  $medicalFacilityType
     * @return \Illuminate\Http\Response
     */
    public function show(MedicalFacilityType $medicalFacilityType)
    {
        return view('admin.medical_facility_types.show', compact('medicalFacilityType'));
    }

    /**
     * Show the form for editing the specified medical facility type.
     *
     * @param  \App\Models\MedicalFacilityType  $medicalFacilityType
     * @return \Illuminate\Http\Response
     */
    public function edit(MedicalFacilityType $medicalFacilityType)
    {
        return view('admin.medical_facility_types.edit', compact('medicalFacilityType'));
    }

    /**
     * Update the specified medical facility type in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MedicalFacilityType  $medicalFacilityType
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MedicalFacilityType $medicalFacilityType)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'en_name' => 'required|string|max:255',
        ]);

        $medicalFacilityType->update($request->all());

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات نوع مرفق طبي"]);

        return redirect()->route(get_area_name().'.medical-facility-types.index')
            ->with('success', 'تم تعديل بيانات نوع مرفق طبي بنجاح.');
    }

    /**
     * Remove the specified medical facility type from storage.
     *
     * @param  \App\Models\MedicalFacilityType  $medicalFacilityType
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicalFacilityType $medicalFacilityType)
    {
        $medicalFacilityType->delete();

        // Log creation with Arabic message
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف نوع مرفق طبي"]);

        return redirect()->route(get_area_name().'.medical-facility-types.index')
            ->with('success', 'تم حذف نوع مرفق طبي بنجاح.');
    }
}
