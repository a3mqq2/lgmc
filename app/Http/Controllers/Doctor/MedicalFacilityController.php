<?php

namespace App\Http\Controllers\Doctor;

use App\Models\MedicalFacility;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileType;

class MedicalFacilityController extends Controller
{
    public function index()
    {
        $facilities = MedicalFacility::latest()->get();
        return view('doctor.medical-facilities.index', compact('facilities'));
    }

    public function create()
    {
        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 1)
        ->where('medical_facility_type_id', request('type'))->get();
        return view('doctor.medical-facilities.create', compact('file_types'));
    }

    public function store(Request $request)
    {
        // 1) Gather the file‐types that *must* be on registration
        $file_types = FileType::where('type','medical_facility')
                                ->where('medical_facility_type_id', $request->type)
                              ->where('for_registration',1)
                              ->get();
    
        // 2) Build your validation rules
        $rules = [
            'name'         => 'required|string|max:255',
            'address'      => 'nullable|string',
            'phone_number' => 'required|string|max:255',
            'type'         => 'required|integer|exists:medical_facility_types,id',
        ];
    
        foreach($file_types as $ft){
            if($ft->is_required){
                // these must be uploaded
                $rules["documents.{$ft->id}"] = 'required|file|mimes:pdf,jpeg,png|max:2048';
            } else {
                // optional
                $rules["documents.{$ft->id}"] = 'nullable|file|mimes:pdf,jpeg,png|max:2048';
            }
        }
    
        $validated = $request->validate($rules);
    
        // 3) Create the facility
        $facility = MedicalFacility::create([
            'name'                     => $validated['name'],
            'address'                  => $validated['address'] ?? null,
            'phone_number'             => $validated['phone_number'],
            'branch_id'                => auth('doctor')->user()->branch_id,
            'manager_id'               => auth('doctor')->id(),
            'membership_status'        => 'pending',
            'medical_facility_type_id' => $validated['type'],
            'user_id'                  => auth('doctor')->id(),
            'branch_id'                => auth('doctor')->user()->branch_id,
        ]);
    
        // 4) Store each uploaded file
        foreach($file_types as $ft){
            // check if file was actually uploaded
            if($request->hasFile("documents.{$ft->id}")){
                $file = $request->file("documents.{$ft->id}");
                $path = $file->store('medical-facilities','public');
    
                $facility->files()->create([
                    'file_name'    => $ft->name,
                    'file_type_id' => $ft->id,
                    'file_path'    => $path,
                ]);
            }
        }
    
        return redirect()
            ->route('doctor.dashboard')
            ->with('success','تم تسجيل المنشأة الطبية بنجاح.');
    }
    
    
}
