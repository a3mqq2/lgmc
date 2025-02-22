<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FileType;
use App\Models\DoctorFile;
use App\Models\DoctorRank;
use App\Models\Log;
use Illuminate\Http\Request;

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
        $doctor_ranks = DoctorRank::all();
        return view('admin.file_types.create', compact('doctor_ranks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "type" => "required|in:doctor,medical_facility",
            "name" => "required|max:225",
            "doctor_rank_id" => "nullable|exists:doctor_ranks,id",
            "doctor_type" => "nullable|in:foreign,visitor,libyan,palestinian",
        ]);

        $fileType = new FileType();
        $fileType->type = $request->type;
        $fileType->name = $request->name;
        $fileType->is_required = $request->is_required ? true : false;

        if ($fileType->type === 'doctor') {
            $fileType->doctor_rank_id = $request->doctor_rank_id;
            $fileType->doctor_type = $request->doctor_type;
        }

        $fileType->save();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تمت إضافة نوع ملف جديد: {$fileType->name}",
            'loggable_id' => $fileType->id,
            'loggable_type' => FileType::class,
            'action' => 'create_file_type',
        ]);

        return redirect()->route(get_area_name() . '.file-types.index')
            ->with('success', 'تمت الإضافة بنجاح.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FileType $fileType)
    {
        $doctor_ranks = DoctorRank::all();
        return view('admin.file_types.edit', compact('fileType', 'doctor_ranks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FileType $fileType)
    {
        $request->validate([
            "type" => "required|in:doctor,medical_facility",
            "name" => "required|max:225",
            "doctor_rank_id" => "nullable|exists:doctor_ranks,id",
            "doctor_type" => "nullable|in:foreign,visitor,libyan,palestinian",
        ]);

        $fileType->type = $request->type;
        $fileType->name = $request->name;
        $fileType->is_required = $request->is_required ? true : false;

        if ($fileType->type === 'doctor') {
            $fileType->doctor_rank_id = $request->doctor_rank_id;
            $fileType->doctor_type = $request->doctor_type;
        } else {
            $fileType->doctor_rank_id = null;
            $fileType->doctor_type = null;
        }

        $fileType->save();

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم تعديل نوع الملف: {$fileType->name}",
            'loggable_id' => $fileType->id,
            'loggable_type' => FileType::class,
            'action' => 'update_file_type',
        ]);

        return redirect()->route(get_area_name() . '.file-types.index')
            ->with('success', 'تم التعديل بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FileType $fileType)
    {
        $doctor_files_count = DoctorFile::where("file_type_id", $fileType->id)->count();

        if ($doctor_files_count > 0) {
            return redirect()->back()->withErrors(['لا يمكنك حذف هذا النوع من الملفات نظراً لوجود ملفات مرتبطة به.']);
        }

        Log::create([
            'user_id' => auth()->id(),
            'details' => "تم حذف نوع الملف: {$fileType->name}",
            'loggable_id' => $fileType->id,
            'loggable_type' => FileType::class,
            'action' => 'delete_file_type',
        ]);

        $fileType->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح.');
    }
}
