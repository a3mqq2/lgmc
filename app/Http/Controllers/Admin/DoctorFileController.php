<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Log;
use App\Models\Doctor;
use App\Models\FileType;
use App\Models\DoctorFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class DoctorFileController extends Controller
{
    /**
     * Display a listing of the doctor files.
     */
    public function index(Doctor $doctor)
    {
        $doctorFiles = $doctor->files;
        return view('general.doctor_files.index', compact('doctorFiles', 'doctor'));
    }

    /**
     * Show the form for creating a new doctor file.
     */
    public function create(Doctor $doctor)
    {
        $fileTypes = FileType::where("type", "doctor")
            ->where('doctor_type', $doctor->type->value)
            ->where('for_registration', 1)
            ->get();

        return view('general.doctor_files.create', compact('doctor', 'fileTypes'));
    }

    /**
     * Store a newly created doctor file in storage.
     */
    public function store(Request $request, Doctor $doctor)
    {
        $request->validate([
            "file_type_id"   => "required|exists:file_types,id",
            'document'       => 'required|file',
        ]);

        try {
            $file     = $request->file('document');
            $fileName = $file->getClientOriginalName();
            $filePath = $file->storeAs('files', $fileName, 'public');

            $doctorFile = DoctorFile::create([
                'doctor_id'    => $doctor->id,
                'file_name'    => $fileName,
                'file_path'    => $filePath,
                "file_type_id" => $request->file_type_id,
                'file_type'    => $file->getMimeType(),
                'uploaded_at'  => now(),
            ]);

            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "تم رفع ملف جديد للطبيب: {$doctor->name}",
                'loggable_id'   => $doctorFile->id,
                'loggable_type' => DoctorFile::class,
                'action'        => 'upload_doctor_file',
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
        return view('general.doctor_files.show', compact('file'));
    }

    /**
     * Show the form for editing the specified doctor file.
     */
    public function edit($doc, $id)
    {
        $file = DoctorFile::findOrFail($id);
        $fileTypes = FileType::where("type", "doctor")
            ->where('doctor_type', $file->doctor->type->value)
            ->get();

        return view('general.doctor_files.edit', compact('file', 'fileTypes'));
    }

    /**
     * Update the specified doctor file in storage.
     */
    public function update(Request $request, $doc,$fileId)
    {
        $file = DoctorFile::findOrFail($fileId);
        $doctor= Doctor::findOrFail($doc);
        $request->validate([
            'file_type_id' => 'required|exists:file_types,id',
            'document'     => 'nullable|file',
        ]);

        try {
            // لو تم رفع ملف جديد احذف القديم وارفع الجديد
            if ($request->hasFile('document')) {
                if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
                    Storage::disk('public')->delete($file->file_path);
                }

                $newDoc   = $request->file('document');
                $fileName = time() . '_' . $newDoc->getClientOriginalName();      
                $filePath = $newDoc->storeAs('files', $fileName, 'public');

                $file->file_name   = $fileName;
                $file->file_path   = $filePath;
                $file->uploaded_at = now();
            }

            // حدّث نوع الملف (file_type_id) دائماً
            $file->file_type_id = $request->file_type_id;
            $file->save();

            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "تم تعديل ملف الطبيب: {$doctor->name}",
                'loggable_id'   => $file->id,
                'loggable_type' => DoctorFile::class,
                'action'        => 'update_doctor_file',
            ]);

            return redirect()
                ->route(get_area_name() . '.doctors.show', $doctor->id)
                ->with('success', 'تم تعديل بيانات ملف الطبيب بنجاح.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->withErrors(['file' => 'فشل تعديل الملف: ' . $e->getMessage()]);
        }
    }

    /**
     * Remove the specified doctor file from storage.
     */
    public function destroy(DoctorFile $file)
    {
        $doctorName = $file->doctor->name;
        $fileId     = $file->id;

        if ($file->file_path && Storage::disk('public')->exists($file->file_path)) {
            Storage::disk('public')->delete($file->file_path);
        }

        $file->delete();

        Log::create([
            'user_id'       => auth()->id(),
            'details'       => "تم حذف ملف الطبيب: {$doctorName}",
            'loggable_id'   => $fileId,
            'loggable_type' => DoctorFile::class,
            'action'        => 'delete_doctor_file',
        ]);

        return redirect()->back()
            ->with('success', 'تم حذف ملف الطبيب بنجاح.');
    }


 public function reorderFiles(Request $request, Doctor $doctor)
    {
        try {
            // التحقق من صحة البيانات
            $request->validate([
                'order' => 'required|array',
                'order.*.id' => 'required|integer|exists:doctor_files,id',
                'order.*.order' => 'required|integer|min:1'
            ]);

            // التحقق من أن جميع الملفات تخص هذا الطبيب
            $fileIds = collect($request->order)->pluck('id');
            $doctorFileIds = $doctor->files()->pluck('id');
     

            // تحديث ترتيب الملفات
            foreach ($request->order as $item) {
                DB::table('doctor_files')
                    ->where('id', $item['id'])
                    ->where('doctor_id', $doctor->id)
                    ->update(['sort_order' => $item['order']]);
            }

            // تسجيل العملية في السجل
            \Log::info('Files reordered for doctor', [
                'doctor_id' => $doctor->id,
                'doctor_name' => $doctor->name,
                'user_id' => auth()->id(),
                'files_count' => count($request->order)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم حفظ الترتيب الجديد بنجاح'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'بيانات غير صحيحة',
                'errors' => $e->errors()
            ], 422);

        } catch (Exception $e) {
            dd($e->getMessage());
            \Log::error('Error reordering files', [
                'doctor_id' => $doctor->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ الترتيب'
            ], 500);
        }
    }
}
