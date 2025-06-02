<?php

namespace App\Http\Controllers\Common;
use Carbon\Carbon;

use App\Models\Log;
use App\Models\User;
use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Pricing;
use App\Models\FileType;
use App\Models\Signature;
use App\Models\Specialty;
use App\Models\DoctorRank;
use PhpParser\Comment\Doc;
use App\Mail\FinalApproval;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Imports\DoctorsImport;
use App\Services\DoctorService;
use Illuminate\Support\Facades\DB;
use App\Imports\DoctorsSheetImport;
use App\Mail\RegisterUnderEditMail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Requests\StoreDoctorRequest;
use App\Http\Requests\UpdateDoctorRequest;
use App\Models\Institution;
use App\Models\MedicalFacility;
use App\Mail\FirstApproval;

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
            return redirect()->route(get_area_name().'.doctors.index', ['type' => request('type') ] )->with('success', 'تم إضافة الطبيب بنجاح');
        } catch (\Exception $e )  {

            return redirect()->route(get_area_name().'.doctors.index', ['type' => request('type') ] )->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }


    public function edit(Doctor $doctor)
    {
        $data = $this->doctorService->getRequirements();
        $data['doctor'] = $doctor;
        $data['file_types'] = FileType::where('type', 'doctor')->where('doctor_type', $doctor->type->value)
        ->where('for_registration', 1)->get();
        return view('general.doctors.edit', $data);
    }

    
    public function show(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        $data['specialties'] = Specialty::all();
        $data['doctor_ranks'] = DoctorRank::where('doctor_type', $doctor->type->value)->get();
        $data['institutions'] = Institution::where('branch_id', $doctor->branch_id)->get();
        $data['medicalFacilities'] = MedicalFacility::where('branch_id', $doctor->branch_id)->get();
        return view('general.doctors.show', $data);
    }


    public function update(UpdateDoctorRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $doctor = Doctor::findOrFail($id);
            $this->doctorService->update($doctor, $validatedData);
            return redirect()->route(get_area_name().'.doctors.index', ['type' =>  $doctor->type->value  ])->with('success', 'تم تعديل الطبيب بنجاح');
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
            return redirect()->route(get_area_name().'.doctors.index',['type' => $doctor->type->value])->with('success', 'تم الموافقة على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function reject(Doctor $doctor)
    {
        try {
            $this->doctorService->reject($doctor);
            return redirect()->route(get_area_name().'.doctors.index', ['type' => $doctor->type->value])->with('success', 'تم الرفض على الطبيب بنجاح');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'حدث خطأ ما يرجى الاتصال بالدعم الفني ' . $e->getMessage() ]);
        }
    }

    public function print_id(Doctor $doctor)
    {
        return view('general.doctors.print-id', ['doctor' => $doctor]);
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
        if(request('doctor_type') == "palestinian") {
            Excel::import(new DoctorsImport, $request->file('file'));
        }
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
    
    

    public function printList(Request $request)
    {
        $query = Doctor::query();
    
        if ($request->filled('doctor_number')) {
            $query->where('doctor_number', $request->doctor_number);
        }
    
        if ($request->filled('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }
    
        if ($request->filled('phone')) {
            $query->where('phone', 'like', '%' . $request->phone . '%');
        }
    
        if ($request->filled('doctor_rank_id')) {
            $query->where('doctor_rank_id', $request->doctor_rank_id);
        }
    
        if ($request->filled('registered_at')) {
            $query->whereDate('registered_at', $request->registered_at);
        }
    
        if ($request->filled('membership_status')) {
            $query->where('membership_status', $request->membership_status);
        }
    
        if ($request->filled('specialization')) {
            $query->where('specialization_id', $request->specialization);
        }
    
        if ($request->filled('last_license_status')) {
            $query->whereHas('latestLicence', function($q) use ($request) {
                $q->where('status', $request->last_license_status);
            });
        }
    
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
    
        $doctors = $query->get();
    
        return view('general.doctors.print_list', compact('doctors'));
    }


    public function change_status(Request $request, Doctor $doctor)
    {
        $request->validate([
            "final_status" => "required|in:under_edit,approved",
            'edit_note' => "required_if:final_status,==,under_edit"
        ]);


        try {
            DB::beginTransaction();
            if($request->final_status == "under_edit")
            {
                $doctor->membership_status = "under_edit";
                $doctor->edit_note = $request->edit_note;
                $doctor->save();

                Mail::to($doctor->email)->send(new RegisterUnderEditMail($doctor));



            } else if($request->final_status == "approved")
            {
                $doctor->membership_status = "under_payment";

                if($request->is_paid)
                {
                    $doctor->membership_status = "active";
                    $doctor->setSequentialIndex();
                    $doctor->makeCode();
                    $doctor->save();
                }
                $doctor->specialty_1_id = $request->specialty_1_id;
                $doctor->doctor_rank_id = $request->doctor_rank_id;
                $doctor->institution_id = $request->institution_id;
                if($request->registered_at)
                {
                    $doctor->registered_at = Carbon::createFromFormat('Y-m-d', $request->registered_at);
                } else {
                    $doctor->registered_at = now();
                }
                $doctor->edit_note = null;
                $doctor->save();


                
                
                $this->createApproveDoctorInvoices($doctor, $request->is_paid);
  
            }
            DB::commit();

            if($request->is_paid)
            {
                $invoice = Invoice::where('doctor_id', $doctor->id)->where('status', 'paid')->first(); 
                if($invoice)
                {
                    return redirect()->route(get_area_name().'.invoices.print', $invoice->id);
                }
            }

            return redirect()->back()
            ->with('success', 'تمت تغيير الحالة بنجاح');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors(['حدث خطأ ما يرجى التواصل مع الدعم الفني رمز الخطأ : ' . $e->getMessage() ]);
        }
    }


    public function createApproveDoctorInvoices($doctor, $is_paid)
    {
        // create membership_invoice
        $invoice = new Invoice();
        $invoice->invoice_number = rand(0,999999999);
        $invoice->description = " فاتورة عضوية طبيب جديد  " . $doctor->name;
        $invoice->user_id = auth()->id();
        $invoice->amount = 0;
        $invoice->status = "unpaid";
        $invoice->doctor_id = $doctor->id;
        $invoice->category = "registration";
        $invoice->save();


        // registeration invoice 
        $pricing_membership = Pricing::where('doctor_type', $doctor->type->value)->where('type','membership')
        ->where('doctor_rank_id', $doctor->doctor_rank_id)->first();
        
        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->description = $pricing_membership->name;
        $invoice_item->amount = $pricing_membership->amount;
        $invoice_item->pricing_id = $pricing_membership->id;
        $invoice_item->save();


        
        $pricing_card_id = Pricing::where('doctor_type', $doctor->type->value)->where('type','card')->first();

        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->description = $pricing_card_id->name;
        $invoice_item->amount = $pricing_card_id->amount;
        $invoice_item->pricing_id = $pricing_card_id->id;
        $invoice_item->save();


        
        $invoice->update([
            'amount' => $invoice->items()->sum('amount'),
        ]);


        Mail::to($doctor->email)
        ->send(new FirstApproval($doctor, $invoice));


        if($is_paid)
        {
            $invoice->status = "paid";
            $invoice->received_by = auth()->id();
            $invoice->received_at = now();
            $invoice->save();

            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            // create transaction 
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = " فاتورة عضوية طبيب جديد  " . $doctor->name . " رقم الفاتورة  " . $invoice->invoice_number;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->save();

            $doctor->membership_status = "active";
            $doctor->membership_expiration_date = $doctor->type->value == "foreign" ?   Carbon::now()->addMonths(6) : Carbon::now()->addMonths(12);
            $doctor->save();
            

        }

    }


    public function print_license(Doctor $doctor)
    {
        $data['doctor'] = $doctor;
        $data['signature'] = Signature::where('is_selected', 1)->when($doctor->branch_id, function($q) use($doctor) {
            $q->where("branch_id", $doctor->branch_id);
        })->first();
        if($doctor->type->value == "foreign")
        {
            return view('general.doctors.print_license_foreign', $data);
        }

        if($doctor->type->value == "palestinian")
        {
            return view('general.doctors.print_license_palestinian', $data);
        }


        if($doctor->type->value == "libyan")
        {
            return view('general.doctors.print_license_libyan', $data);
        }


        return view('general.doctors.print_license', $data);
    }
    
}
