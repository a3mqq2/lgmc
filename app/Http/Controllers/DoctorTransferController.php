<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Branch;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\DoctorTransfer;
use Illuminate\Support\Facades\DB;

class DoctorTransferController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $query = DoctorTransfer::query();

        if (request()->has('status')) {
            $query->where('status', request('status'));
        }

        if (request()->q) {
            $query->where('id', request()->q)
                  ->orWhere('note', 'like', '%' . request()->q . '%');
        }

        $query->where(function ($q) {
            $q->where('from_branch_id', auth()->user()->branch_id)
              ->orWhere('to_branch_id', auth()->user()->branch_id);
        })->with(['doctor', 'fromBranch', 'toBranch', 'createdBy', 'approvedBy', 'rejectedBy']);

        $doctorTransfers = $query->latest()->paginate(10);

        return view('user.doctor_transfers.index', compact('doctorTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors  = Doctor::where('branch_id', auth()->user()->branch_id)->select('id', 'name')->get();
        $branches = Branch::all();

        return view('user.doctor_transfers.create', compact('doctors', 'branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'    => 'required|exists:doctors,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'note'         => 'nullable|string',
        ]);

        $doctor = Doctor::findOrFail($request->doctor_id);

        $doctorTransfer = DoctorTransfer::create([
            'doctor_id'      => $request->doctor_id,
            'from_branch_id' => $doctor->branch_id,
            'to_branch_id'   => $request->to_branch_id,
            'created_by'     => auth()->id(),
            'note'           => $request->note,
            'status'         => 'pending',
        ]);

        Log::create([
            'user_id'      => auth()->id(),
            'details'      => "تم إنشاء طلب نقل للطبيب: {$doctorTransfer->doctor->name} من الفرع {$doctorTransfer->fromBranch->name} إلى الفرع {$doctorTransfer->toBranch->name}",
            'loggable_id'  => $doctorTransfer->doctor->id,
            'loggable_type'=> Doctor::class,
            'action'       => 'create_doctor_transfer',
        ]);

        return redirect()->route(get_area_name().'.doctor-transfers.index')
                         ->with('success', 'تم طلب نقل الطبيب بنجاح.');
    }

    /**
     * Show the specified resource.
     */
    public function show(DoctorTransfer $doctorTransfer)
    {
        return view('user.doctor_transfers.show', compact('doctorTransfer'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route(get_area_name().'.doctor-transfers.index')
                             ->with('error', 'لا يمكن تعديل الطلب بعد الموافقة أو الرفض.');
        }

        $doctors  = Doctor::where('branch_id', auth()->user()->branch_id)->select('id', 'name')->get();
        $branches = Branch::where('id', '!=', auth()->user()->branch_id)->get();

        return view('user.doctor_transfers.edit', compact('doctorTransfer', 'doctors', 'branches'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route(get_area_name().'.doctor-transfers.index')
                             ->with('error', 'لا يمكن تعديل الطلب بعد الموافقة أو الرفض.');
        }

        $request->validate([
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'note'         => 'nullable|string',
        ]);

        $doctorTransfer->update([
            'to_branch_id' => $request->to_branch_id,
            'note'         => $request->note,
        ]);

        Log::create([
            'user_id'      => auth()->id(),
            'details'      => "تم تعديل طلب نقل للطبيب: {$doctorTransfer->doctor->name}. الفرع الجديد: {$doctorTransfer->toBranch->name}",
            'loggable_id'  => $doctorTransfer->doctor->id,
            'loggable_type'=> Doctor::class,
            'action'       => 'edit_doctor_transfer',
        ]);

        return redirect()->route(get_area_name().'.doctor-transfers.index')
                         ->with('success', 'تم تحديث طلب النقل بنجاح.');
    }

    public function approve(DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()
                ->route(get_area_name() . '.doctor-transfers.index')
                ->with('error', 'هذا الطلب تم اتخاذ إجراء عليه مسبقًا.');
        }
    
        DB::transaction(function () use ($doctorTransfer) {
    
            $oldDoctor   = $doctorTransfer->doctor;
            $emailBackup = $oldDoctor->email;   // نحتفظ بالإيميل لإعادة استخدامه
    
            /* 1) البحث عن ملف طبيب مُعلَّق مسبقًا في الفرع المستهدف */
            $existingDoctor = Doctor::where('branch_id', $doctorTransfer->to_branch_id)
                ->where('national_number', $oldDoctor->national_number)   // غيّر العمود إذا كنت تستخدم معرفًا آخر
                ->where('membership_status', 'suspended')
                ->latest()
                ->first();
    
            /* 2) تعليق الطبيب في الفرع الحالي */
            $oldDoctor->membership_status = 'suspended';
            $oldDoctor->suspended_reason  = 'تم النقل إلى الفرع ' . $doctorTransfer->toBranch->name;
            $oldDoctor->email             = null;          // إتاحة الإيميل
            $oldDoctor->save();
    
            /** 3) اختيار ملف الهدف: قديم مفعَّل أو جديد منسوخ **/
            if ($existingDoctor) {
    
                /* ─ إعادة تفعيل الملف القديم في الفرع المستهدف ─ */
                $targetDoctor                    = $existingDoctor;
                $targetDoctor->membership_status = 'active';
                $targetDoctor->suspended_reason  = null;
                $targetDoctor->email             = $emailBackup;   // إعادة الإيميل إليه
                $targetDoctor->updated_at        = now();
                $targetDoctor->save();
    
            } else {
    
                /* ─ إنشاء نسخة جديدة في الفرع المستهدف ─ */
                $targetDoctor                    = $oldDoctor->replicate();
                $targetDoctor->branch_id         = $doctorTransfer->to_branch_id;
                $targetDoctor->membership_status = 'active';
                $targetDoctor->suspended_reason  = null;
                $targetDoctor->email             = $emailBackup;
                $targetDoctor->created_at        = now();
                $targetDoctor->updated_at        = now();
                $targetDoctor->save();
    
                $targetDoctor->setSequentialIndex();
                $targetDoctor->makeCode();
                $targetDoctor->save();
            }
    
            /* 4) نسخ الملفات إلى الملف المفعَّل (إن لم تكن موجودة) */
            foreach ($oldDoctor->files as $file) {
                $newFile            = $file->replicate();
                $newFile->doctor_id = $targetDoctor->id;
                $newFile->save();
            }

            /* 6) تحديث حالة طلب النقل */
            $doctorTransfer->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
    
            /* 7) Log */
            Log::create([
                'user_id'       => auth()->id(),
                'details'       => "تمت الموافقة على طلب نقل الطبيب: {$oldDoctor->name} إلى الفرع {$doctorTransfer->toBranch->name}. "
                                  . ($existingDoctor ? 'تم إعادة تفعيل ملفه السابق.' : 'تم إنشاء ملف جديد وتعليق الملف القديم.'),
                'loggable_id'   => $targetDoctor->id,
                'loggable_type' => Doctor::class,
                'action'        => 'approve_doctor_transfer',
            ]);
        });
    
        return redirect()
            ->route(get_area_name() . '.doctor-transfers.index')
            ->with('success', 'تمت الموافقة على طلب النقل، وتم التعامل مع ملف الطبيب وفق المتطلبات.');
    }
    
    /**
     * Reject the specified transfer request.
     */
    public function reject(Request $request, DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route(get_area_name().'.doctor-transfers.index')
                             ->with('error', 'هذا الطلب تم اتخاذ إجراء عليه مسبقًا.');
        }

        $request->validate([
            'rejected_reason' => 'required|string',
        ]);

        $doctorTransfer->update([
            'status'          => 'rejected',
            'rejected_by'     => auth()->id(),
            'rejected_reason' => $request->rejected_reason,
            'rejected_at'     => now(),
        ]);

        Log::create([
            'user_id'      => auth()->id(),
            'details'      => "تم رفض طلب نقل الطبيب: {$doctorTransfer->doctor->name}. السبب: {$request->rejected_reason}",
            'loggable_id'  => $doctorTransfer->doctor->id,
            'loggable_type'=> Doctor::class,
            'action'       => 'reject_doctor_transfer',
        ]);

        return redirect()->route(get_area_name().'.doctor-transfers.index')
                         ->with('success', 'تم رفض طلب النقل.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route(get_area_name().'.doctor-transfers.index')
                             ->with('error', 'لا يمكن حذف الطلب بعد الموافقة أو الرفض.');
        }

        $doctorName = $doctorTransfer->doctor->name;
        $doctorTransfer->delete();

        Log::create([
            'user_id'      => auth()->id(),
            'details'      => "تم حذف طلب نقل الطبيب: {$doctorName}",
            'loggable_id'  => $doctorTransfer->doctor->id,
            'loggable_type'=> Doctor::class,
            'action'       => 'delete_doctor_transfer',
        ]);

        return redirect()->route(get_area_name().'.doctor-transfers.index')
                         ->with('success', 'تم حذف طلب النقل.');
    }
}
