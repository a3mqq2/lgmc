<?php

namespace App\Http\Controllers\Common;

use App\Models\FileType;
use App\Enums\DoctorType;
use Illuminate\Http\Request;
use App\Services\InvoiceService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\{Auth, DB};
use PhpOffice\PhpSpreadsheet\Calculation\Financial\Securities\Price;
use App\Models\{Doctor, Invoice, Licence, Log, MedicalFacility, Pricing, Transaction, Vault};

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
/**
 * Store a newly created licence in storage.
 */
public function store(Request $request)
{
    // 2) Build validation rules
    $rules = [
        'licensable_type'     => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
        'licensable_id'       => 'required|integer',
        'issued_date'         => 'required|date',
        'expiry_date'         => 'required|date|after_or_equal:issued_date',
        'medical_facility_id' => 'nullable|integer',
    ];

    $validated = $request->validate($rules);

    try {
        DB::transaction(function () use ($request, $validated, $file_types) {
            // 3) Find the licensable entity (Doctor or MedicalFacility)
            $licensable = app($validated['licensable_type'])
                               ->findOrFail($validated['licensable_id']);

            // 4) Prevent duplicate licences
            if (Licence::where('licensable_type', $validated['licensable_type'])
                       ->where('licensable_id',   $validated['licensable_id'])
                       ->where('issued_date',     $validated['issued_date'])
                       ->where('expiry_date',     $validated['expiry_date'])
                       ->exists()
            ) {
                throw new \Exception('هذا الترخيص موجود بالفعل');
            }

            // 5) Create the licence record
            $licence = Licence::create([
                'licensable_type'     => $validated['licensable_type'],
                'licensable_id'       => $validated['licensable_id'],
                'issued_date'         => $validated['issued_date'],
                'expiry_date'         => $validated['expiry_date'],
                'branch_id'           => $licensable->branch_id,
                'status'              => 'under_payment',
                'created_by'          => Auth::id(),
                'doctor_type'         => $licensable instanceof Doctor ? $licensable->type : null,
                'medical_facility_id' => $licensable instanceof Doctor ? $validated['medical_facility_id'] : null,
            ]);

            // 6) Inline pricing logic & invoice creation
            $pricingMap = [
                DoctorType::Libyan->value      => [1=>7, 2=>8, 3=>9, 4=>10, 5=>11, 6=>12],
                DoctorType::Palestinian->value => [1=>59,2=>60,3=>61,4=>62,5=>63,6=>64],
                DoctorType::Visitor->value     => [3=>28,4=>29,5=>30],
                DoctorType::Foreign->value     => [1=>19,2=>20,3=>21,4=>22,5=>23,6=>24],
            ];

            if ($licensable instanceof Doctor) {
                $typeVal   = $licensable->type;
                $rankId    = $licensable->doctor_rank_id;
                $pricingId = $pricingMap[$typeVal][$rankId] ?? null;
            } else {
                // facility: first licence 67, subsequent 43
                $pricingId = $licensable->licenses()->count() === 0 ? 67 : 43;
            }

            if ($pricingId && $pricing = Pricing::find($pricingId)) {
                Invoice::create([
                    'invoice_number'   => 'LIC_' . uniqid(),
                    'amount'           => $pricing->amount,
                    'branch_id'        => $licensable->branch_id,
                    'description'      => 'إصدار إذن مزاولة لـ ' . $licensable->name,
                    'invoiceable_type' => get_class($licensable),
                    'invoiceable_id'   => $licensable->id,
                    'licence_id'       => $licence->id,
                    'pricing_id'       => $pricing->id,
                    'user_id'          => Auth::id(),
                    'status'           => 'unpaid',
                ]);
            }

        });
    } catch (\Exception $e) {
        return redirect()->back()->withErrors($e->getMessage());
    }

    return redirect()->back()->with('success', 'تم إضافة إذن مزاولة بنجاح.');
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

   
        if($licence->status != "active") {
            return redirect()->back()->withErrors(['لا يمكنك طباعة اذن مزاولة في هذه الحاله التي بها']);
        }   

        Log::create([
            "user_id" => auth()->id(),
            "details" => " تمت طباعة اذن المزاولة " . $licence->id, 
            'loggable_id' => $licence->licensable_id,
            'loggable_type' => Doctor::class,
            "action" => "print_licence",
        ]);



        if($licence->licensable_type == "App\Models\Doctor") {

            if($licence->licensable->type == DoctorType::Libyan) {
                return view('general.licences.print', compact('licence'));
            } else {
                return view('general.licences.print-foreign', compact('licence'));
            }

        } else if($licence->licensable_type == "App\Models\MedicalFacility") {
            return view('general.licences.print-medical', compact('licence'));
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
