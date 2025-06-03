<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Models\Branch;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\DoctorTransfer;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;

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
        $branches = Branch::where('id', '!=', auth()->user()->branch_id)
                          ->select('id', 'name')
                          ->get();
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
            $emailBackup = $oldDoctor->email;
    
            $existingDoctor = Doctor::where('branch_id', $doctorTransfer->to_branch_id)
                ->where('national_number', $oldDoctor->national_number)
                ->where('membership_status', 'suspended')
                ->latest()
                ->first();
    
            $oldDoctor->membership_status = 'suspended';
            $oldDoctor->suspended_reason  = 'تم النقل إلى الفرع ' . $doctorTransfer->toBranch->name;
            $oldDoctor->email             = null;
            $oldDoctor->save();
    
            if ($existingDoctor) {
    
                $targetDoctor                    = $existingDoctor;
                $targetDoctor->membership_status = 'active';
                $targetDoctor->suspended_reason  = null;
                $targetDoctor->email             = $emailBackup;
                $targetDoctor->updated_at        = now();
    
            } else {
    
                $targetDoctor                    = $oldDoctor->replicate();
                $targetDoctor->branch_id         = $doctorTransfer->to_branch_id;
                $targetDoctor->membership_status = 'active';
                $targetDoctor->suspended_reason  = null;
                $targetDoctor->email             = $emailBackup;
                $targetDoctor->created_at        = now();
                $targetDoctor->updated_at        = now();
            }
    
            $targetDoctor->regenerateCode();
    
            foreach ($oldDoctor->files as $file) {
                $newFile            = $file->replicate();
                $newFile->doctor_id = $targetDoctor->id;
                $newFile->save();
            }
    
            $doctorTransfer->update([
                'status'      => 'approved',
                'approved_by' => auth()->id(),
                'approved_at' => now(),
            ]);
    
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

    /**
     * Branch transfers report (JSON).
     */
    public function report(Request $request): JsonResponse
    {
        $branchId = auth()->user()->branch_id;

        $baseQuery = DoctorTransfer::where(function($q) use ($branchId){
            $q->where('from_branch_id',$branchId)
              ->orWhere('to_branch_id',$branchId);
        });

        $startDate = $request->start ?? now()->subYear()->startOfYear()->toDateString();
        $endDate   = $request->end   ?? now()->toDateString();

        $statusData = $baseQuery->whereBetween('created_at',[$startDate,$endDate])
            ->selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total','status');

        $monthly = $baseQuery->whereBetween('created_at',[$startDate,$endDate])
            ->selectRaw("DATE_FORMAT(created_at, '%Y-%m') as month, COUNT(*) as total")
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total','month');

        return response()->json([
            'stats' => [
                'total'    => $statusData->sum(),
                'pending'  => $statusData['pending'] ?? 0,
                'approved' => $statusData['approved'] ?? 0,
                'rejected' => $statusData['rejected'] ?? 0,
            ],
            'monthly' => $monthly,
        ]);
    }

    // app/Http/Controllers/DoctorTransferController.php  (append inside the class)

    public function print(Request $request)
    {
        $branchId = auth()->user()->branch_id;

        $transfers = DoctorTransfer::where(function ($q) use ($branchId) {
                $q->where('from_branch_id', $branchId)
                ->orWhere('to_branch_id', $branchId);
            })
            ->when($request->filled('status'),    fn($q) => $q->whereIn('status', (array) $request->status))
            ->when($request->filled('from_date'), fn($q) => $q->whereDate('created_at', '>=', $request->from_date))
            ->when($request->filled('to_date'),   fn($q) => $q->whereDate('created_at', '<=', $request->to_date))
            ->with(['doctor', 'fromBranch', 'toBranch', 'createdBy'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('user.doctor_transfers.print', [
            'transfers' => $transfers,
            'printed_at'=> now()->format('Y-m-d H:i'),
            'filters'   => $request->only(['status','from_date','to_date'])
        ]);
    }

}
