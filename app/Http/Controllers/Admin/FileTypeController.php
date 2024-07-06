<?php

namespace App\Http\Controllers\Admin;

use App\Models\FileType;
use App\Models\DoctorFile;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FileTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $fileTypes = FileType::all();
        return view('admin.file_types.index', compact('fileTypes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.file_types.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "type" => "required|in:doctor,medical_facility",
            "name" => "required|max:225",
            "is_required" => "required|boolean|in:0,1",
        ]);

        $fileType = new FileType();
        $fileType->type  = $request->type;
        $fileType->name = $request->name;
        $fileType->is_required = $request->is_required;
        $fileType->save();

        // Log creation with details
        Log::create(['user_id' => auth()->user()->id, 'details' => "تمت إضافة نوع ملف جديد: " . $fileType->name]);

        return redirect()->route(get_area_name().'.file-types.index')
            ->with('success', 'تمت الاضافة بنجاح');
    }

    /**
     * Display the specified resource.
     */
    public function show(FileType $fileType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileType $fileType)
    {
        return view('admin.file_types', compact('fileType'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileType $fileType)
    {
        $request->validate([
            "type" => "required|in:doctor,medical_facility",
            "name" => "required|max:225",
            "is_required" => "required|boolean|in:0,1",
        ]);

        $fileType->type  = $request->type;
        $fileType->name = $request->name;
        $fileType->is_required = $request->is_required;
        $fileType->save();

        // Log update with details
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم تعديل نوع الملف: " . $fileType->name]);

        return redirect()->route(get_area_name().'.file-types.index')
            ->with('success', 'تم التعديل بنجاح');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileType $fileType)
    {
        $doctor_files = DoctorFile::where("file_type_id", $fileType->id)->count();
        if ($doctor_files > 0) {
            return redirect()->back()->withErrors(['لا يمكنك حذف هـذا النوع من الملفات نظرا لوجود ملفات مسجله تحت منه']);
        }

        // Log deletion with details before deletion
        Log::create(['user_id' => auth()->user()->id, 'details' => "تم حذف نوع الملف: " . $fileType->name]);

        $fileType->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح');
    }
}
