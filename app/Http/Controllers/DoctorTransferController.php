<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Models\Doctor;
use Illuminate\Http\Request;
use App\Models\DoctorTransfer;

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
        });

        $query->with(['doctor', 'fromBranch', 'toBranch', 'createdBy', 'approvedBy', 'rejectedBy']);

        $doctorTransfers = $query->latest()->paginate(10);
        return view('user.doctor_transfers.index', compact('doctorTransfers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->select('id', 'name')->get();
        $branches = Branch::select('id', 'name')->where('id', '!=', auth()->user()->branch_id)->get();
        return view('user.doctor_transfers.create', compact('doctors','branches'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'note' => 'nullable|string',
        ]);

        $doctorTransfer = DoctorTransfer::create([
            'doctor_id' => $request->doctor_id,
            'from_branch_id' => auth()->user()->branch_id,
            'to_branch_id' => $request->to_branch_id,
            'created_by' => auth()->id(),
            'note' => $request->note,
            'status' => 'pending',
        ]);

        return redirect()->route('user.doctor-transfers.index')->with('success', 'تم طلب نقل الطبيب بنجاح');
    }

    /**
     * Display the specified resource.
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
            return redirect()->route('user.doctor-transfers.index')->with('error', 'لا يمكن تعديل الطلب بعد الموافقة أو الرفض.');
        }

        return view('user.doctor_transfers.edit', compact('doctorTransfer'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route('user.doctor-transfers.index')->with('error', 'لا يمكن تعديل الطلب بعد الموافقة أو الرفض.');
        }

        $request->validate([
            'to_branch_id' => 'required|exists:branches,id|different:from_branch_id',
            'note' => 'nullable|string',
        ]);

        $doctorTransfer->update([
            'to_branch_id' => $request->to_branch_id,
            'note' => $request->note,
        ]);

        return redirect()->route('user.doctor-transfers.index')->with('success', 'تم تحديث طلب النقل بنجاح');
    }

    /**
     * Approve the specified transfer request.
     */
    public function approve(DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route('user.doctor-transfers.index')->with('error', 'هذا الطلب تم اتخاذ إجراء عليه مسبقًا.');
        }


        $doctor = Doctor::find($doctorTransfer->doctor_id);
        $doctor->update([
            'branch_id' => $doctorTransfer->to_branch_id,
        ]);

        $doctorTransfer->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('user.doctor-transfers.index')->with('success', 'تمت الموافقة على طلب النقل.');
    }

    /**
     * Reject the specified transfer request.
     */
    public function reject(Request $request, DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route('user.doctor-transfers.index')->with('error', 'هذا الطلب تم اتخاذ إجراء عليه مسبقًا.');
        }

        $request->validate([
            'rejected_reason' => 'required|string',
        ]);

        $doctorTransfer->update([
            'status' => 'rejected',
            'rejected_by' => auth()->id(),
            'rejected_reason' => $request->rejected_reason,
            'rejected_at' => now(),
        ]);

        return redirect()->route('user.doctor-transfers.index')->with('success', 'تم رفض طلب النقل.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorTransfer $doctorTransfer)
    {
        if ($doctorTransfer->status !== 'pending') {
            return redirect()->route('user.doctor-transfers.index')->with('error', 'لا يمكن حذف الطلب بعد الموافقة أو الرفض.');
        }

        $doctorTransfer->delete();

        return redirect()->route('user.doctor-transfers.index')->with('success', 'تم حذف طلب النقل.');
    }
}
