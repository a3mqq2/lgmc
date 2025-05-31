<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\FileType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\DoctorFile;

class ProfileController extends Controller
{
    public function change_password()
    {
        return view('general.profile.password');
    }

    public function change_password_store(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:8',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            Log::create([
                'user_id' => auth()->id(),
                'details' => 'فشل في محاولة تغيير كلمة المرور بسبب كلمة مرور حالية غير صحيحة',
                'loggable_id' => auth()->id(),
                'loggable_type' => \App\Models\User::class,
                "action" => "change_password_failed",
            ]);

            return back()->withErrors(['current_password' => 'كلمة المرور الحالية غير صحيحة']);
        }

        auth()->user()->update([
            'password' => Hash::make($request->password),
        ]);

        Log::create([
            'user_id' => auth()->id(),
            'details' => 'تم تغيير كلمة المرور بنجاح',
            'loggable_id' => auth()->id(),
            'loggable_type' => \App\Models\User::class,
            "action" => "change_password_success",
        ]);

        return redirect()->to(url()->previous() . '?change-password=1')->with('success', 'تم تغيير كلمة المرور بنجاح');
    }


    public function update(UpdateDoctorRequest $request)
    {
        $doctor = auth('doctor')->user();
    
        $doctor->update($request->validated());
    

        $doctor->membership_status = "under_approve";
        $doctor->save();

        if ($request->hasFile('reupload_files')) {
            $reuploads = $request->file('reupload_files');
    
            foreach ($reuploads as $fileTypeId => $file) {
                if (! $file) {
                    continue;
                }
    
                $path = $file->store("documents/{$doctor->id}", 'public');
                $doctorFile = DoctorFile::find($fileTypeId);
                $doctorFile->file_path = $path;
                $doctorFile->file_name = $file->getClientOriginalName();
                $doctorFile->save();
            }
        }
    
        return redirect()
            ->route('doctor.dashboard')
            ->with('success', 'تم تحديث البيانات والمستندات بنجاح');
    }
    
}
