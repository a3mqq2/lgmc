<?php

namespace App\Http\Controllers\Common;
use Carbon\Carbon;

use App\Models\Log;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\FileType;
use PhpParser\Comment\Doc;
use Illuminate\Http\Request;
use App\Imports\DoctorsImport;
use App\Services\DoctorService;
use App\Imports\DoctorsSheetImport;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;

class DoctorController extends Controller
{
    protected $doctorService;

    public function __construct(DoctorService $doctorService)
    {
        $this->doctorService = $doctorService;
    }


    public function index(Request $request)
    {
        // $request->validate(['type' => 'required|in:libyan,palestinian,foreign','visitor']);
        $doctors = $this->doctorService->getDoctors();
        $data = $this->doctorService->getRequirements();
        $data['doctors'] = $doctors;
        return view('general.doctors.index', $data);
    }

    public function create(Request $request)
    {
        // $request->validate(['type' => 'required|in:libyan,palestinian,foreign','visitor']);
        $data = $this->doctorService->getRequirements();
        return view('general.doctors.create',$data);
    }

    public function store(StoreDoctorRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->doctorService->create($validatedData);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم إضافة الطبيب بنجاح');
        } catch (\Exception $e )  {

            return redirect()->back()->withInput()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني' . $e->getMessage()]);
        }
    }


    public function edit(Doctor $doctor)
    {
        $data = $this->doctorService->getRequirements();
        $data['doctor'] = $doctor;
        $data['file_types'] = FileType::where('type', 'doctor')->get();
        return view('general.doctors.edit', $data);
    }

    
    public function show(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        return view('general.doctors.show', $data);
    }


    public function update(UpdateDoctorRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $doctor = Doctor::findOrFail($id);
            $this->doctorService->update($doctor, $validatedData);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم تعديل الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function destroy(Doctor $doctor)
    {
        try {
            $this->doctorService->delete($doctor);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم حذف الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function print(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        return view('general.doctors.print', $data);
    }

    public function approve(Doctor $doctor)
    {
        try {
            $this->doctorService->approve($doctor);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم الموافقة على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function reject(Doctor $doctor)
    {
        try {
            $this->doctorService->reject($doctor);
            return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم الرفض على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function print_id(Doctor $doctor)
    {
        return view('general.doctors.print_id', ['doctor' => $doctor]);
    }

    public function import(Request $request)
    {
        return view('general.doctors.import');
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);
        // import excel
        Excel::import(new DoctorsSheetImport, $request->file('file'));
        return redirect()->route(get_area_name().'.doctors.index')->with('success', 'تم إضافة الأطباء بنجاح');
    }


    public function ban(Doctor $doctor)
    {

        // Check if the doctor is currently banned
        if ($doctor->membership_status->value === 'banned') {
            // ✅ UNBAN LOGIC


            $newStatus = $doctor->membership_expiration_date && $doctor->membership_expiration_date > now() ? 'active' : 'inactive';
    
            $doctor->update(['membership_status' => $newStatus]);
    
            // Log the unban action
            \App\Models\Log::create([
                'user_id' => auth()->id(),
                'branch_id' => auth()->user()->branch_id ?? null,
                'action' => 'رفع الحظر عن الطبيب',
                'details' => "تم رفع الحظر عن الطبيب: {$doctor->name} (الرقم النقابي: {$doctor->id}) - الحالة الآن: {$newStatus}",
                'loggable_id' => $doctor->id,
                'loggable_type' => \App\Models\Doctor::class,
            ]);
    
            // ✅ If membership expired, create an invoice for renewal
            if ($newStatus === 'inactive') {
                $this->doctorService->createInvoice($doctor);
            }
    
            return redirect()->back()->with('success', 'تم رفع الحظر عن الطبيب بنجاح.');
        }
    
        // ✅ BAN LOGIC
        $doctor->update(['membership_status' => 'banned']);
    
        foreach($doctor->licenses as $licence)
        {
            $licence->status = "revoked";
            $licence->save();
        }

        \App\Models\Log::create([
            'user_id' => auth()->id(),
            'branch_id' => auth()->user()->branch_id ?? null,
            'action' => 'حظر الطبيب',
            'details' => "تم حظر الطبيب: {$doctor->name} (الرقم النقابي: {$doctor->id})",
            'loggable_id' => $doctor->id,
            'loggable_type' => \App\Models\Doctor::class,
        ]);
    
        return redirect()->back()->with('success', 'تم حظر الطبيب بنجاح.');
    }
    
    
}
