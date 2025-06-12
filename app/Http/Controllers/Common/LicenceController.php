<?php

namespace App\Http\Controllers\Common;

use App\Models\FileType;
use App\Enums\DoctorType;
use App\Models\InvoiceItem;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB};
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;
use App\Models\{Doctor, Invoice, Licence, Log, MedicalFacility, Pricing, Signature, Transaction, Vault};
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Sign;

class LicenceController extends Controller
{


    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $request->validate([
            'type' => 'required|in:doctors,facilities',
            'doctor_type' => 'nullable',
            'status' => 'nullable',
        ]);
    
        $query = Licence::query();
    
        if ($request->type === 'doctors') {
            $query->whereHasMorph('licensable', [Doctor::class]);
        } else {
            $query->whereHasMorph('licensable', [MedicalFacility::class]);
        }
    
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->doctor_type && $request->type === 'doctors') {
            $query->where('doctor_type', $request->doctor_type);
        }

        if($request->code)
        {
            $query->where('code', 'like', '%' . $request->code . '%');
        }
        
        if ($request->search) {
            $model = $request->type === 'doctors' ? Doctor::class : MedicalFacility::class;
            $query->whereHasMorph('licensable', [$model], function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            });
        }
        
        if ($request->issued_from_date) {
            $query->whereDate('issued_date', '>=', $request->issued_from_date);
        }

        if ($request->issued_to_date) {
            $query->whereDate('issued_date', '<=', $request->issued_to_date);
        }

        if ($request->expiry_from_date) {
            $query->whereDate('expiry_date', '>=', $request->expiry_from_date);
        }

        if ($request->expiry_to_date) {
            $query->whereDate('expiry_date', '<=', $request->expiry_to_date);
        }


    
        if (get_area_name() !== 'admin') {
            $query->where('branch_id', Auth::user()->branch_id);
        }
    
        return view('general.licences.index', [
            'licences' => $query->orderByDesc('index')->paginate(50)
        ]);
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        if(get_area_name() == "user") {
            $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->where('type', $request->doctor_type)->latest()->get();
            $medicalFacilities = MedicalFacility::select('id','name')->latest()->get();
        } else {
            $doctors = Doctor::where('type', request("doctor_type"))->latest()->get();
            $medicalFacilities = MedicalFacility::latest()->get();
        }

        $file_types = FileType::where('type', 'medical_facility')->where('for_registration', 0)->get();

        return view('general.licences.create', compact('doctors', 'medicalFacilities', 'request','file_types'));
    }


    public function store(Request $request)
    {
        // التحقق من صحة البيانات
        $validated = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'doctor_rank_id' => 'required|exists:doctor_ranks,id',
            'specialty_id' => 'required|exists:specialties,id',
            'issue_date' => 'required|date',
            'medical_facility_id' => 'required|exists:medical_facilities,id',
        ]);

        try {
            DB::beginTransaction();


            $doctor = Doctor::findOrFail($validated['doctor_id']);

            $licence = new Licence();
            $licence->doctor_id = $validated['doctor_id'];
            $licence->doctor_rank_id = $validated['doctor_rank_id'];
            $licence->issued_date = $validated['issue_date'];
            $licence->expiry_date  = $doctor->type->value == "libyan" ? Carbon::parse($validated['issue_date'])->addYear() : Carbon::parse($validated['issue_date'])->addMonths(6);
            $licence->status = 'under_payment';
            $licence->doctor_type = $doctor->type->value ?? null;
            $licence->created_by = Auth::id();
            $licence->specialty_id = $request->specialty_id;
            $licence->doctor_rank_id = $request->doctor_rank_id;
            $licence->workin_medical_facility_id = $request->medical_facility_id ?? null;
            $licence->amount = 0;
            $licence->save();


            $pricing = Pricing::where('doctor_type', $doctor->type->value)
            ->where('type', 'license')
            ->where('doctor_rank_id', $doctor->doctor_rank_id)
            ->first();

            if(!$pricing)
            {
                return redirect()->back()->withErrors(['لم يتم وضع تسعيره اتصل بالدعم الفني']);
            }


            // new invoice

            $medicalFacility = MedicalFacility::find($request->medical_facility_id);
            $invoice = new Invoice();
            $invoice->invoice_number = rand(0,999999999);
            $invoice->description = "فاتورة اذن مزاولة جديد للطبيب " . $doctor->name . ' بالعمل في  ' . $medicalFacility->name;
            $invoice->user_id = auth()->id();
            $invoice->amount = $pricing->amount;
            $invoice->status = "unpaid";
            $invoice->doctor_id = $doctor->id;
            $invoice->category = "licence";
            $invoice->licence_id = $licence->id;
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


            DB::commit();

            return redirect()
                ->route(get_area_name() . '.doctors.show', $doctor)
                ->with('success', 'تم إضافة إذن المزاولة بنجاح. رقم الإذن: ' . $licence->code);

        } catch (\Exception $e) {
            DB::rollback();
            
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['حدث خطأ أثناء إضافة إذن المزاولة: ' . $e->getMessage()]);
        }
    }

    

    /**
     * Display the specified resource.
     */
    public function show(Licence $licence)
    {
        $vaults = Vault::when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->get();

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $licencable = Doctor::findOrFail($licence->licensable_id);
            $type = 'doctors';
        } else {
            $licencable = MedicalFacility::findOrFail($licence->licensable_id);
            $type = 'facilities';
        }


        return view('general.licences.show', compact('licence','vaults','type'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Licence $licence)
    {
     
        if(get_area_name() == "user") {
            $doctors = Doctor::where('branch_id', auth()->user()->branch_id)->latest()->get();
            $medicalFacilities = MedicalFacility::where('branch_id', auth()->user()->branch_id)->latest()->get();
        } else {
            $doctors = Doctor::latest()->get();
            $medicalFacilities = MedicalFacility::latest()->get();
        }

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $licencable = Doctor::findOrFail($licence->licensable_id);
            $type = 'doctors';
        } else {
            $licencable = MedicalFacility::findOrFail($licence->licensable_id);
            $type = 'facilities';
        }



        return view('general.licences.edit', compact('licence', 'doctors', 'medicalFacilities','type'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Licence $licence)
    {
  

        $validatedData = $request->validate([
            'licensable_type' => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
            'licensable_id' => 'required|integer',
            'issued_date' => 'required|date',
            'expiry_date' => 'required|date',
            'medical_facility_id' => 'nullable|integer',
        ]);

        $licencable = null;
        if($request->licensable_type == "App\Models\Doctor") {
            $validatedData['branch_id'] = Doctor::findOrFail($request->licensable_id)->branch_id;
            $licencable = Doctor::findOrFail($request->licensable_id);
        } else {
            $validatedData['branch_id'] =  MedicalFacility::findOrFail($request->licensable_id)->branch_id;
            $licencable = MedicalFacility::findOrFail($request->licensable_id);
        }

        $details = '';

        if($request->licensable_id != $licence->licensable_id) {
            $details .= "تم تحديث المرخص من " . $licence->licensable->name . ' الى ' . $licencable->name;
        }


        if($request->issued_date != $licence->issued_date) {
            $details .= "تم تحديث تاريخ الاصدار من " . $licence->issued_date . ' الى ' . $licencable->issued_date;
        }


        if($request->expiry_date != $licence->expiry_date) {
            $details .= "تم تحديث تاريخ الانتهاء من " . $licence->expiry_date . ' الى ' . $licencable->expiry_date;
        }



        $licence->update($validatedData);

 

        Log::create([
            'user_id' => Auth::id(),
            'branch_id' => Auth::user()->branch_id,
            'details' => "تم تعديل الاذن مزاولة: نوع الاذن مزاولة {$request->licensable_type}، معرف الاذن مزاولة {$request->licensable_id}",
            'loggable_id' => $licence->licensable_id,
            'loggable_type' => Doctor::class,
            "action" => "edit_licence",
        ]);


        $type = $request->licensable_type == "App\Models\Doctor" ? 'doctors' : 'facilities';

        return redirect()->back()
            ->with('success', 'تم تعديل الاذن مزاولة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Licence $licence)
{
    DB::transaction(function () use ($licence) {

        /* -------------------------------------------------
         | 1. تحديد نوع الترخيص (أطباء / منشآت)
         -------------------------------------------------*/
        $type = ($licence->licensable_type === Doctor::class) ? 'doctors' : 'facilities';

        /* -------------------------------------------------
         | 2. التعامل مع الفاتورة المرتبطة (استرداد إن لزم)
         -------------------------------------------------*/
        $invoice = \App\Models\Invoice::where('licence_id', $licence->id)->first();

        if ($invoice) {
            $vault = Auth::user()->branch->vault;   // حساب فرع المستخدم

            // إذا كانت الفاتورة مدفوعة – استرد المبلغ
            if ($invoice->status->value === 'paid') {

                if ($invoice->total_invoice_id) {
                    // الفاتورة جزء من فاتورة كلية
                    $totalInvoice = \App\Models\TotalInvoice::find($invoice->total_invoice_id);

                    if ($totalInvoice) {
                        $refundDesc          = "استرداد مبلغ فاتورة الإذن {$licence->id} من فاتورة كلية رقم {$totalInvoice->invoice_number}";
                        $vault->balance     -= $invoice->amount;
                        $vault->save();

                        \App\Models\Transaction::create([
                            'user_id'             => Auth::id(),
                            'desc'                => $refundDesc,
                            'amount'              => $invoice->amount,
                            'branch_id'           => Auth::user()->branch_id,
                            'type'                => 'withdrawal',
                            'vault_id'            => $vault->id,
                            'balance'             => $vault->balance,
                        ]);

                        $totalInvoice->total_amount -= $invoice->amount;
                        ($totalInvoice->total_amount <= 0) ? $totalInvoice->delete() : $totalInvoice->save();
                    }
                } else {
                    // فاتورة فردية
                    $refundDesc          = "استرداد مبلغ فاتورة الإذن {$licence->id} بعد الحذف";
                    $vault->balance     -= $invoice->amount;
                    $vault->save();

                    \App\Models\Transaction::create([
                        'user_id'             => Auth::id(),
                        'desc'                => $refundDesc,
                        'amount'              => $invoice->amount,
                        'branch_id'           => Auth::user()->branch_id,
                        'type'                => 'withdrawal',
                        'vault_id'            => $vault->id,
                        'balance'             => $vault->balance,
                    ]);
                }
            }

            // حذف الفاتورة بأي حال
            $invoice->delete();
        }

        /* -------------------------------------------------
         | 3. حذف الإذن وسجلّاته
         -------------------------------------------------*/
        $licence->logs()->delete();
        $licence->delete();

        Log::create([
            'user_id'       => Auth::id(),
            'branch_id'     => Auth::user()->branch_id,
            'details'       => "تم حذف إذن مزاولة: معرف {$licence->id}",
            'loggable_id'   => $licence->licensable_id,
            'loggable_type' => $licence->licensable_type,
            'action'        => 'delete_licence',
        ]);

        /* -------------------------------------------------
         | 4. إعادة ترقيم وتصحيح الأكواد بعد الحذف
         -------------------------------------------------*/
        $branchId = $licence->branch_id;
        $year     = $licence->created_at->year;
        $model    = $licence->licensable_type;                  // Doctor أو MedicalFacility
        $prefix   = ($model === Doctor::class) ? 'LIC' : 'PERM';

        $remaining = Licence::with('branch')
            ->where('branch_id', $branchId)
            ->whereYear('created_at', $year)
            ->where('licensable_type', $model)
            ->orderBy('created_at')
            ->get();

        $i = 1;
        foreach ($remaining as $l) {
            $l->index = $i;
            $l->code  = $l->branch->code . '-' . $prefix . '-' . $year . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);
            $l->saveQuietly();
            $i++;
        }
    });

    $type = ($licence->licensable_type === Doctor::class) ? 'doctors' : 'facilities';

    return redirect()
        ->back()
        ->with('success', 'تم حذف إذن المزاولة بنجاح وإعادة ترقيم الأكواد.');
}
    
    

    public function approve(Request $request, Licence $licence) {
        $request->validate([
            "notes" => "nullable",
        ]);

        try {
            DB::beginTransaction();
            $notLibyan = '';
            if(get_area_name() == "user" && $licence->status != "under_approve_branch") {
                return redirect()->back()->withErrors(['لا يمكنك الموافقة على اذن مزاولة في هذه الحاله التي بها']);
            }

            if(get_area_name() == "admin" && $licence->status != "under_approve_admin") {
                return redirect()->back()->withErrors(['لا يمكنك الموافقة على اذن مزاولة في هذه الحاله التي بها']);
            }

            $invoice = Invoice::where('licence_id', $licence->id)->first();

                if($invoice->isPaid())
                {
                    $licence->status = "active";
                } else {
                    $licence->status = "under_payment";
                }
                $licence->save();

            DB::commit();
            return redirect()->back()->with('success', 'تمت الموافقه بنجاح');
        } catch(\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }

    public function print(Licence $licence) {


        $signature = null;
        if(get_area_name() == "admin")
        {
            $signature = Signature::whereNull('branch_id')->where('is_selected', 1)->first();
        } else {
            $signature = Signature::where('branch_id', $licence->branch_id)->where('is_selected', 1)->first();
        }
        
        if($licence->doctor)
        {
            if($licence->doctor->type == DoctorType::Libyan) {
                $signature = Signature::where('branch_id', $licence->doctor->branch_id)->where('is_selected', 1)->first();
                return view('general.doctors.print_license_libyan', compact('licence','signature'));
            } else if($licence->doctor->type == DoctorType::Visitor) {
                return view('general.licences.print-visitor', compact('licence','signature'));
            } else {
                return view('general.licences.print-foreign', compact('licence','signature'));
            }
        }

    }

    public function payment(Request $request, Licence $licence) {
        $request->validate([
            "vault_id" => "required",
            "amount" => "required",
            "notes" => "nullable",
        ]);


        if(auth()->user()->branch_id != $licence->branch_id) {
            abort(404);
        }

        try {
            DB::beginTransaction();
            $vault = Vault::findOrFail($request->vault_id);
            $licence->update(['amount' => $request->amount, 'status' => "active"]);

            Log::create([
                "user_id" => auth()->id(),
                "details" => "تم دفع رسوم اذن المزاولة " . $licence->id . 'وذلك بقيمة ' . $licence->amount,
                'loggable_id' => $licence->licensable_id,
                'loggable_type' => Doctor::class,
                'action' => "payment"
            ]);



      


            $vault->increment('balance', $request->amount);
            $transaction = new Transaction();
            $transaction->user_id = auth()->id();
            $transaction->desc = "دفع رسوم تجديد العقد  " . $licence->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->type = "deposit";
            $transaction->balance = $vault->balance;
            $transaction->vault_id = $vault->id;
            $transaction->save();

            DB::commit();
            return redirect()->back()->with('success', 'تم دفع قيمة اذن المزاولة بنجاح');
            // TODO : send an email for the doctor
            // TODO : ADD THE PROPER TRANSACTION TYPE ID 
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    }
}
