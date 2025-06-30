<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MedicalFacilityFileController extends Controller
{
    public function store(Request $request, $medicalFacilityId)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'file_type_id' => 'required|exists:file_types,id',
        ]);

        $file = $request->file('file');
        $filePath = $file->storeAs(
            'medical_facilities/' . $medicalFacilityId,
            time() . '_' . $file->getClientOriginalName(),
            'public'
        );

        $medicalFacility = \App\Models\MedicalFacility::findOrFail($medicalFacilityId);
        $fileType = \App\Models\FileType::findOrFail($request->file_type_id);
        $medicalFacility->files()->create([
            'file_path' => $filePath,
            'file_type_id' => $request->file_type_id,
            'file_name' => $fileType->name,
            'uploaded_at' => now(),
            'order_number' => $fileType->order_number,
        ]);

        return redirect()->back()->with('success', 'تم رفع الملف بنجاح');

    }


    public function destroy($fileId)
    {
        $file = \App\Models\MedicalFacilityFile::findOrFail($fileId);
        $file->delete();

        // Optionally, delete the file from storage
        \Storage::disk('public')->delete($file->file_path);

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح');
    }


    public function update(Request $request, $medicalFacility, $fileId)
    {
        $request->validate([
            'file' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx|max:2048',
            'file_type_id' => 'required|exists:file_types,id',
        ]);
    
        $file = \App\Models\MedicalFacilityFile::findOrFail($fileId);
        $fileType = \App\Models\FileType::findOrFail($request->file_type_id);
    
        if ($request->hasFile('file')) {
            // حذف الملف القديم إن وجد
            if ($file->file_path && \Storage::disk('public')->exists($file->file_path)) {
                \Storage::disk('public')->delete($file->file_path);
            }
    
            $newFile = $request->file('file');
            $filePath = $newFile->storeAs(
                'medical_facilities/' . $file->medical_facility_id,
                time() . '_' . $newFile->getClientOriginalName(),
                'public'
            );
    
            $file->file_path   = $filePath;
            $file->file_name   = $fileType->name;
            $file->uploaded_at = now();
        }
    
        $file->file_type_id = $request->file_type_id;
        $file->order_number = $fileType->order_number;
        $file->save();
    
        return redirect()->back()->with('success', 'تم تحديث الملف بنجاح');
    }
    
}
