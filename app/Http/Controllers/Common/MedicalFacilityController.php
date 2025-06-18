<?php

namespace App\Http\Controllers\Common;
use App\Models\Branch;

use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Pricing;
use App\Models\FileType;
use App\Mail\ApprovalEmail;
use App\Models\InvoiceItem;
use App\Models\Transaction;
use App\Mail\RejectionEmail;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\MedicalFacilityFile;
use App\Models\MedicalFacilityType;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use App\Mail\MedicalFacilityRejectMail;
use App\Imports\ImportMedicalFacilities;
use App\Services\MedicalFacilityService;
use App\Http\Requests\StoreMedicalFacilityRequest;
use App\Models\Signature;
use App\Models\Vault;

class MedicalFacilityController extends Controller
{
    protected $medicalFacilityService;

    public function __construct(MedicalFacilityService $medicalFacilityService)
    {
        $this->medicalFacilityService = $medicalFacilityService;
    }

    public function index(Request $request)
    {
        // Retrieve filtered/paginated results from the service
        $medicalFacilities = $this->medicalFacilityService->getAll(
            $request->only(['q', 'ownership', 'medical_facility_type_id','membership_status','code']), 
            10
        );

        $medicalFacilityTypes = MedicalFacilityType::all();

        return view('general.medical-facilities.index', compact('medicalFacilities', 'medicalFacilityTypes'));
    }

    public function create()
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 1)->get();
        $doctors = auth()->user()->branch ? auth()->user()->branch->doctors()->whereHas('licenses', function($q) {
            $q->where('status', 'active');
        }) : Doctor::all();
        $branches = Branch::all();
        return view('general.medical-facilities.create', compact('medicalFacilityTypes','file_types','doctors','branches'));
    }

    public function store(StoreMedicalFacilityRequest $request)
    {

        try {
            $this->medicalFacilityService->create($request->validated());
        } catch(\Exception $e) {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
        
        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم إنشاء منشأة طبية جديدة بنجاح.');
    }

    public function show(MedicalFacility $medicalFacility)
    {
        $medicalFacilityFileTypes = FileType::where('type', 'medical_facility')->where('facility_type', (($medicalFacility->type) == "single" ? "private" : "services") )->get();
        return view('general.medical-facilities.show', compact('medicalFacility','medicalFacilityFileTypes'));
    }

    public function edit($id)
    {
        $medicalFacilityTypes = MedicalFacilityType::all();
        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 1)->get();
        $doctors = Doctor::where('membership_status','active')->where('type', 'libyan')->get();
        $branches = Branch::all();
        $medicalFacility = MedicalFacility::findOrFail($id);
        return view('general.medical-facilities.edit', compact('medicalFacility','medicalFacilityTypes','file_types','doctors','branches'));
    }

    public function update(Request $request, MedicalFacility $medicalFacility)
    {
      

        try {
            $this->medicalFacilityService->update($medicalFacility, $request->all());
        } catch(\Exception $e)
        {
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم تحديث بيانات منشأة طبية بنجاح.');
    }

    public function destroy(MedicalFacility $medicalFacility)
    {
        $this->medicalFacilityService->delete($medicalFacility);

        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم حذف منشأة طبية بنجاح.');
    }

    public function import()
    {
        return view('general.medical-facilities.import');
    }

    public function import_store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
        ]);


        Excel::import(new ImportMedicalFacilities, $request->file('file'));

        
        return redirect()->route(get_area_name().'.medical-facilities.index')
            ->with('success', 'تم استيراد المنشآت الطبية بنجاح.');
    }

    public function file_destroy($fileId)
    {
        $file = MedicalFacilityFile::findOrFail($fileId);
        $file->delete();

        return redirect()->back()->with('success', 'تم حذف الملف بنجاح.');
    }




    public function change_status(Request $request, MedicalFacility $medicalFacility)
    {
        $request->validate([
            'status' => 'required|in:active,under_edit',
            'edit_reason' => 'required_if:status,==,under_edit|max:255',
        ]);


        if($request->status == "under_edit")
        {
            $medicalFacility->membership_status = "under_edit"; 
            $medicalFacility->edit_reason = $request->edit_reason;
            $medicalFacility->save();

            try {
                Mail::to($medicalFacility->manager->email)->send(new MedicalFacilityRejectMail($medicalFacility, $request->edit_reason));
            } catch (\Exception $e) {
                return redirect()->back()->withErrors(['error' => 'فشل في إرسال البريد الإلكتروني: ' . $e->getMessage()]);
            }
        }




        if($request->status == "active")
        {
            $medicalFacility->edit_reason = null;
            $medicalFacility->membership_status = "under_payment"; 

            if(!$medicalFacility->renew_number)
            {
                $medicalFacility->setSequentialIndex();
                $medicalFacility->makeCode();
            }
           
            $medicalFacility->save();
            $this->createMedicalFacilityInvoice($medicalFacility, $request->is_paid);
        }

        return redirect()->back()->with('success', 'تم تغيير حالة المنشأة الطبية بنجاح.');
    }

    public function createMedicalFacilityInvoice(MedicalFacility $medicalFacility, $is_paid)
    {
       
        $pricing = $medicalFacility->renew_number ?  Pricing::where('entity_type', 'medical_facility')
        ->where('type', 'renew')
        ->first() : Pricing::where('entity_type', 'medical_facility')->first();
        
        if (!$pricing) {
            return redirect()->back()->withErrors(['error' => 'لا يوجد تسعير للمنشآت الطبية']);
        }


        $invoice = new Invoice();
        $invoice->invoice_number = rand(0,999999999);
        $invoice->description = $medicalFacility->renew_number ? 
            "فاتورة تجديد عضوية منشأة طبية رقم {$medicalFacility->renew_number} - {$medicalFacility->name}" :
            "فاتورة عضوية منشأة طبية جديدة - {$medicalFacility->name}";

        $invoice->user_id = auth()->id();
        $invoice->amount = 0;
        $invoice->status = "unpaid";
        $invoice->doctor_id = $medicalFacility->manager->id;
        $invoice->category = $medicalFacility->renew_number ? 'medical_facility_renew' : 'medical_facility_registration';
        $invoice->save();

        $invoice_item = new InvoiceItem();
        $invoice_item->invoice_id = $invoice->id;
        $invoice_item->description = $pricing->name;
        $invoice_item->amount = $pricing->amount;
        $invoice_item->pricing_id = $pricing->id;
        $invoice_item->save();
        $invoice->update([
            'amount' => $invoice->items()->sum('amount'),
        ]);

        if ($is_paid) {
            $invoice->status = 'paid';
            $invoice->received_at = now();
            $invoice->received_by = auth()->id();
            $invoice->save();

            // Update medical facility status to active
            $medicalFacility->membership_status = "active";
            $medicalFacility->membership_expiration_date = now()->addYear();
            $medicalFacility->save();


              // create license
              $medicalFacility->licenses()->delete();
              $license = new Licence();
              $license->medical_facility_id = $medicalFacility->id;
              $license->issued_date = now();
              $license->expiry_date = now()->addYear();
              $license->status = "active";
              $license->created_by = auth()->id();
              $license->amount = 0; // Assuming amount is 0 for now, adjust as needed
              $license->save();

            // Create a transaction for the payment
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();

            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = $medicalFacility->renew_number ? 
                "إيداع مبلغ فاتورة تجديد منشأة طبية رقم {$medicalFacility->renew_number} - {$medicalFacility->name}" :
                "إيداع مبلغ فاتورة عضوية منشأة طبية جديدة - {$medicalFacility->name}";
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->save();
            
        }

        return $invoice;
    }

    public function print_license(MedicalFacility $medicalFacility)
    {
        $license = $medicalFacility->licenses()->latest()->first();
        if (!$license) {
            return redirect()->back()->withErrors(['error' => 'لا يوجد رخصة لهذه المنشأة الطبية']);
        }

        if(get_area_name() == "admin")
        {
            $signature =  Signature::where('is_selected', 1)->where('branch_id', null)->first();
        } else {
            $signature =  Signature::where('is_selected', 1)->where('branch_id', auth()->user()->branch_id)->first();
        }

        return view('general.medical-facilities.license', compact('medicalFacility', 'license','signature'));
    }
}
