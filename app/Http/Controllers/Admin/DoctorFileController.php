<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\DoctorFile;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Models\FileType;

class DoctorFileController extends Controller
{
    /**
     * Display a listing of the doctor files.
     */
    public function index(Doctor $doctor)
    {
        $doctorFiles = $doctor->files;
        return view('admin.doctor_files.index', compact('doctorFiles', 'doctor'));
    }

    /**
     * Show the form for creating a new doctor file.
     */
    public function create(Doctor $doctor)
    {
        $fileTypes = FileType::where("type", "doctor")->get();
        return view('general.doctor_files.create', compact('doctor', 'fileTypes'));
    }

    /**
     * Store a newly created doctor file in storage.
     */
    public function store(Request $request, Doctor $doctor)
    {
        $request->validate([
            "file_type_id" => "required|exists:file_types,id",
            'document' => 'required|file',
        ]);

        try {
            $file = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('files', $fileName, 'public');

            $doctorFile = DoctorFile::create([
                'doctor_id' => $doctor->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                "file_type_id" => $request->file_type_id,
                'file_type' => $file->getMimeType(),
                'uploaded_at' => now(),
            ]);

            Log::create([
                'user_id' => auth()->id(),
                'details' => "تم رفع ملف جديد للطبيب: {$doctor->name}",
                'loggable_id' => $doctorFile->id,
                'loggable_type' => DoctorFile::class,
                'action' => 'upload_doctor_file',
            ]);

            return redirect()->route(get_area_name() . '.doctors.show', $doctor->id)
                ->with('success', 'تم رفع الملف بنجاح.');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'فشل رفع الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified doctor file.
     */
    public function show(DoctorFile $file)
    {
        return view('admin.doctor_files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified doctor file.
     */
    public function edit(DoctorFile $file)
    {
        return view('admin.doctor_files.edit', compact('file'));
    }

    /**
     * Update the specified doctor file in storage.
     */
    public function update(Request $request, DoctorFile $file)
    {
        $request->validate([
            'file_name' => 'required|string|max:255',
            'file_path' => 'required|string',
            'file_type' => 'required|string|max:255',
            'uploaded_at' => 'nullable|date',
        ]);

        $file->update($request->all());

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل بيانات ملف الطبيب: {$file->doctor->name}",
            'loggable_id' => $file->id,
            'loggable_type' => DoctorFile::class,
            'action' => 'update_doctor_file',
        ]);

        return redirect()->route(get_area_name() . '.doctors.files.index', $file->doctor_id)
            ->with('success', 'تم تعديل بيانات ملف الطبيب بنجاح.');
    }

    /**
     * Remove the specified doctor file from storage.
     */
    public function destroy(DoctorFile $file)
    {
        $doctorName = $file->doctor->name;
        $fileId = $file->id;
        $file->delete();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف ملف الطبيب: {$doctorName}",
            'loggable_id' => $fileId,
            'loggable_type' => DoctorFile::class,
            'action' => 'delete_doctor_file',
        ]);

        return redirect()->back()
            ->with('success', 'تم حذف ملف الطبيب بنجاح.');
    }
}
