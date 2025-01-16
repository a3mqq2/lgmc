<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use App\Models\Log;
use App\Models\FileType;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityFile;


class MedicalFacilityFileController extends Controller
{


    public function create(MedicalFacility $medicalFacility)
    {
        $fileTypes = FileType::where("type", "medical_facility")->get();
        return view('general.medical_facilities_files.create', compact('medicalFacility','fileTypes'));
    }

  
    public function store(Request $request, MedicalFacility $medicalFacility)
    {
        // Validate the incoming file
        $request->validate([
            "file_type_id" => "required",
            'document' => 'required', // Adjust allowed file types and max file size as needed
        ]);

        try {
            // Store the uploaded file
            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('/public/medical_facilites', $fileName,'public'); 

            // Create a record in the database
            MedicalFacilityFile::create([
                'medical_facility_id' => $medicalFacility->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                "file_type_id" => $request->file_type_id,
                'file_type' => $file->getMimeType(), // Get the MIME type of the file
                'uploaded_at' => now(),
            ]);

            // Log creation with details
            Log::create(['user_id' => auth()->user()->id, 'details' => "تم رفع ملف جديد للمنشأة: " . $medicalFacility->name]);

            // Redirect back with success message
            return redirect()->route(get_area_name() . '.medical-facilities.show', $medicalFacility->id)
                ->with('success', 'تم رفع الملف بنجاح.');
        } catch (\Exception $e) {
            // If an exception occurs during file upload
            $errorMessage = $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'فشل رفع الملف: ' . $errorMessage]);
        }
    }


    public function destroy( MedicalFacility $medicalFacility, MedicalFacilityFile $medical_facility_file)
    {

        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف ملف المنشأة : " . $medicalFacility->name]);
        $medical_facility_file->delete();
        return redirect()->back()
            ->with('success', 'تم حذف ملف المنشأه بنجاح.');
    }
}
