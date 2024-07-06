<?php

namespace App\Http\Controllers\Admin;

use App\Models\Doctor;
use App\Models\DoctorFile;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\FileType;

class DoctorFileController extends Controller
{
    /**
     * Display a listing of the doctor files.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function index(Doctor $doctor)
    {
        $doctorFiles = $doctor->files;
        return view('admin.doctor_files.index', compact('doctorFiles', 'doctor'));
    }

    /**
     * Show the form for creating a new doctor file.
     *
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function create(Doctor $doctor)
    {
        $fileTypes = FileType::where("type", "doctor")->get();
        return view('general.doctor_files.create', compact('doctor','fileTypes'));
    }

    /**
     * Store a newly created doctor file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Doctor  $doctor
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Doctor $doctor)
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
            $filePath = $file->storeAs('/public/doctor_files', $fileName); // Store file in storage/app/public/doctor_files directory

            // Create a record in the database
            DoctorFile::create([
                'doctor_id' => $doctor->id,
                'file_name' => $fileName,
                'file_path' => $filePath,
                "file_type_id" => $request->file_type_id,
                'file_type' => $file->getMimeType(), // Get the MIME type of the file
                'uploaded_at' => now(),
            ]);

            // Log creation with details
            Log::create(['user_id' => auth()->user()->id, 'details' => "تم رفع ملف جديد للطبيب: " . $doctor->name]);

            // Redirect back with success message
            return redirect()->route(get_area_name() . '.doctors.show', $doctor->id)
                ->with('success', 'تم رفع الملف بنجاح.');
        } catch (\Exception $e) {
            // If an exception occurs during file upload
            $errorMessage = $e->getMessage();
            return redirect()->back()
                ->withInput()
                ->withErrors(['file' => 'فشل رفع الملف: ' . $errorMessage]);
        }
    }

    /**
     * Display the specified doctor file.
     *
     * @param  \App\Models\DoctorFile  $file
     * @return \Illuminate\Http\Response
     */
    public function show(DoctorFile $file)
    {
        return view('admin.doctor_files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified doctor file.
     *
     * @param  \App\Models\DoctorFile  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(DoctorFile $file)
    {
        return view('admin.doctor_files.edit', compact('file'));
    }

    /**
     * Update the specified doctor file in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\DoctorFile  $file
     * @return \Illuminate\Http\Response
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

        // Log update with details
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل بيانات ملف الطبيب: " . $file->doctor->name]);

        return redirect()->route(get_area_name().'.doctors.files.index', $file->doctor_id)
            ->with('success', 'تم تعديل بيانات ملف الطبيب بنجاح.');
    }

    /**
     * Remove the specified doctor file from storage.
     *
     * @param  \App\Models\DoctorFile  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(DoctorFile $file)
    {
        $doctorName = $file->doctor->name; // Save the doctor's name for logging
        $file->delete();

        // Log deletion with details
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف ملف الطبيب: " . $doctorName]);

        return redirect()->back()
            ->with('success', 'تم حذف ملف الطبيب بنجاح.');
    }
}
