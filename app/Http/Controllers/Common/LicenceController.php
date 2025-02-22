<?php

namespace App\Http\Controllers\Common;

use App\Enums\DoctorType;
use App\Http\Controllers\Controller;

use App\Models\Log; 
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\LicenceLog;
use PhpParser\Comment\Doc;
use Illuminate\Http\Request;
use App\Models\MedicalFacility;
use App\Models\Pricing;
use App\Models\Transaction;
use App\Models\Vault;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            "type" => "required|in:doctors,facilities",
            'doctor_type' => "nullable",
        ]);
        $query = Licence::query();

        if ($request->has('type')) {
            if ($request->type === 'doctors') {
                $query->whereHasMorph('licensable', Doctor::class);
            } else {
                $query->whereHasMorph('licensable', MedicalFacility::class);
            }
        }


        if($request->status) {
            $query->where('status', $request->status);
        }

        if(get_area_name() != "admin") {
            $query->where('branch_id', auth()->user()->branch_id);
        }
        $licences = $query->with('licensable')->latest()->paginate(10);
        return view('general.licences.index', compact('licences'));
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

        return view('general.licences.create', compact('doctors', 'medicalFacilities', 'request'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {





        $request->validate([
            'licensable_type' => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
            'licensable_id' => 'required|integer',
            'issued_date' => 'required|date',
            'expiry_date' => 'required|date',
        ]);
        

        try {
            DB::beginTransaction();
            if($request->licensable_type == "App\Models\Doctor") {
                $licencable = Doctor::findOrFail($request->licensable_id);
                $checkIfHasLicence = Licence::where('licensable_type', 'App\Models\Doctor')
                    ->where('licensable_id', $licencable->id)->where('status','!=', 'expired')->first();
                if($checkIfHasLicence) {
                    return redirect()->back()->withErrors(['هذا الطبيب لديه اذن مزاولة مفعل بالفعل']);
                } else {
                    $licence = new Licence();
                    $licence->licensable_type = 'App\Models\Doctor';
                    $licence->licensable_id = $licencable->id;
                    $licence->issued_date = $request->issued_date;
                    $licence->expiry_date = $request->expiry_date;
                    $licence->branch_id = $licencable->branch_id;
                    $licence->doctor_type = $licencable->type;
                    $licence->medical_facility_id = $request->medical_facility_id;
                    $licence->created_by = auth()->id();
                    $licence->status = "active";
                    $licence->save();

                    if($licencable->type->value == DoctorType::Libyan->value)
                    {
                        if(!in_array($licencable->doctor_rank_id, [1,2]))
                        {

                            $licence->status = "under_approve_admin";
                        } else {
                            $licence->status = "under_payment";
                        }



                        if($licencable->doctor_rank_id == 1) {
                            $getPrice = Pricing::find(7);
                        } else if($licencable->doctor_rank_id == 2) {
                            $getPrice = Pricing::find(8);
                        } else if($licencable->doctor_rank_id == 3) {
                            $getPrice = Pricing::find(9);
                        } else if($licencable->doctor_rank_id == 4) {
                            $getPrice = Pricing::find(10);
                        } else if($licencable->doctor_rank_id == 5) {
                            $getPrice = Pricing::find(11);
                        } else if($licencable->doctor_rank_id == 6) {
                            $getPrice = Pricing::find(12);
                        }

                    } else if($licencable->type->value == DoctorType::Palestinian->value)
                    {
                        $licence->status = "under_approve_admin";

                        if($licencable->doctor_rank_id == 1) {
                            $getPrice = Pricing::find(59);
                        } else if($licencable->doctor_rank_id == 2) {
                            $getPrice = Pricing::find(60);
                        } else if($licencable->doctor_rank_id == 3) {
                            $getPrice = Pricing::find(61);
                        } else if($licencable->doctor_rank_id == 4	) {
                            $getPrice = Pricing::find(62);
                        } else if($licencable->doctor_rank_id == 5) {
                            $getPrice = Pricing::find(63);
                        } else if($licencable->doctor_rank_id == 6	) {
                            $getPrice = Pricing::find(64);
                        }
                    } else if($licencable->type->value == DoctorType::Visitor->value)
                    {

                        $licence->status = "under_approve_admin";

                        if($licencable->doctor_rank_id == 3) {
                            $getPrice = Pricing::find(28);
                        } else if($licencable->doctor_rank_id == 4) {
                            $getPrice = Pricing::find(29);
                        } else if($licencable->doctor_rank_id == 5) {
                            $getPrice = Pricing::find(30);
                        }
                    } else if($licencable->type->value == DoctorType::Foreign->value)
                    {
                        $licence->status = "under_approve_admin";
                        
                        if($licencable->doctor_rank_id == 1) {
                            $getPrice = Pricing::find(19);
                        } else if($licencable->doctor_rank_id == 2) {
                            $getPrice = Pricing::find(20);
                        } else if($licencable->doctor_rank_id == 3) {
                            $getPrice = Pricing::find(21);
                        } else if($licencable->doctor_rank_id == 4	) {
                            $getPrice = Pricing::find(22);
                        } else if($licencable->doctor_rank_id == 5) {
                            $getPrice = Pricing::find(23);
                        } else if($licencable->doctor_rank_id == 6	) {
                            $getPrice = Pricing::find(24);
                        }
                    } else if($licencable->type->value == DoctorType::Palestinian->value)
                    {
                        $licence->status = "under_approve_admin";
                        if($licencable->doctor_rank_id == 1) {
                            $getPrice = Pricing::find(59);
                        } else if($licencable->doctor_rank_id == 2) {
                            $getPrice = Pricing::find(60);
                        } else if($licencable->doctor_rank_id == 3) {
                            $getPrice = Pricing::find(61);
                        } else if($licencable->doctor_rank_id == 4	) {
                            $getPrice = Pricing::find(62);
                        } else if($licencable->doctor_rank_id == 5) {
                            $getPrice = Pricing::find(63);
                        } else if($licencable->doctor_rank_id == 6	) {
                            $getPrice = Pricing::find(64);
                        }
                    }


                    if($getPrice) {
                        $invoice = new Invoice();
                        $invoice->invoice_number = "LIC" . last_invoice_id();
                        $invoice->amount = $getPrice->amount;
                        $invoice->branch_id = $licencable->branch_id;
                        $invoice->description = "تكلفة إنشاء اذن مزاولة للطبيب " . $licencable->name;
                        $invoice->invoiceable_type = 'App\Models\Doctor';
                        $invoice->invoiceable_id = $licencable->id;
                        $invoice->licence_id = $licence->id;
                        $invoice->pricing_id = $getPrice->id;
                        $invoice->user_id = auth()->id();
                        $invoice->status = "unpaid";
                        $invoice->save();

                        // $vault = auth()->user()->branch ? auth()->user()->branch->vault : \App\Models\Vault::first();
                        // $this->invoiceService->markAsPaid($vault, $invoice, $invoice->description);
                    }

                    $licence->save();
                }

            } else {
                $licencable = MedicalFacility::findOrFail($request->licensable_id);
                $checkIfHasLicence = Licence::where('licensable_type', 'App\Models\MedicalFacility')
                    ->where('licensable_id', $licencable->id)
                    ->first();

                    if($checkIfHasLicence) {
                        if($checkIfHasLicence->status != "expired")
                        {
                            return redirect()->back()->withErrors(['هذه المنشأة لديها اذن مزاولة مفعل بالفعل']);
                        } else {
                            $licence = new Licence();
                            $licence->licensable_type = 'App\Models\MedicalFacility';
                            $licence->licensable_id = $licencable->id;
                            $licence->issued_date = $request->issued_date;
                            $licence->expiry_date = $request->expiry_date;
                            $licence->branch_id = $licencable->branch_id;
                            $licence->status = "under_approve_admin";
                            $licence->created_by = auth()->id();
                            $licence->save();
                            $getPrice = Pricing::find(77);
                            if($getPrice) {
                                $invoice = new Invoice();
                                $invoice->invoice_number = "MED" . last_invoice_id();
                                $invoice->amount = $getPrice->amount;
                                $invoice->branch_id = $licencable->branch_id;
                                $invoice->description = "رسوم تجديد اذن مزاولة للمنشأة ";
                                $invoice->invoiceable_type = 'App\Models\MedicalFacility';
                                $invoice->invoiceable_id = $licencable->id;
                                $invoice->licence_id = $licence->id;
                                $invoice->pricing_id = $getPrice->id;
                                $invoice->user_id = auth()->id();
                                $invoice->status = "unpaid";
                                $invoice->save();
                            }
                        }
                    } else {
                        $licence = new Licence();
                        $licence->licensable_type = 'App\Models\MedicalFacility';
                        $licence->licensable_id = $licencable->id;
                        $licence->issued_date = $request->issued_date;
                        $licence->expiry_date = $request->expiry_date;
                        $licence->branch_id = $licencable->branch_id;
                        $licence->status = "under_approve_admin";
                        $licence->created_by = auth()->id();
                        $licence->save();

                        $getPrice = Pricing::find(76);
                        if($getPrice) {
                            $invoice = new Invoice();
                            $invoice->invoice_number = "MED" . last_invoice_id();
                            $invoice->amount = $getPrice->amount;
                            $invoice->branch_id = $licencable->branch_id;
                            $invoice->description = "تكلفة إنشاء منشأة طبية";
                            $invoice->invoiceable_type = 'App\Models\MedicalFacility';
                            $invoice->invoiceable_id = $licencable->id;
                            $invoice->licence_id = $licence->id;
                            $invoice->pricing_id = $getPrice->id;
                            $invoice->user_id = auth()->id();
                            $invoice->status = "unpaid";
                            $invoice->save();
                        }
                    }
            }
            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollBack();
            return redirect()->back()->withErrors([$e->getMessage(), $e->getLine()]);
        }


        $type = $invoice->invoiceable_type == "App\Models\Doctor" ? "doctors" : "facilities";
        $doctor_type = $licence->doctor_type;
        return redirect()->route(get_area_name().'.licences.index', ['type' => $type, 'doctor_type' => $doctor_type])
            ->with('success', 'تم إضافة الاذن مزاولة بنجاح.');
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
        if($licence->status != "under_approve_branch" && $licence->status != "under_approve_admin") {
            return redirect()->back()->withErrors(['لا يمكنك تعديل هذا الاذن ليس في حاله صحيحه']);
        }

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
        if($licence->status != "under_approve_branch" && $licence->status != "under_approve_admin") {
            return redirect()->back()->withErrors(['لا يمكنك تعديل هذا الاذن ليس في حاله صحيحه']);
        }


        $validatedData = $request->validate([
            'licensable_type' => 'required|in:App\Models\Doctor,App\Models\MedicalFacility',
            'licensable_id' => 'required|integer',
            'issued_date' => 'required|date',
            'expiry_date' => 'required|date',
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

        return redirect()->route(get_area_name().'.licences.index', ['type' => $licence->type, 'status' => $licence->status])
            ->with('success', 'تم تعديل الاذن مزاولة بنجاح.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Licence $licence)
    {
        if($licence->status != "under_approve_branch") {
            return redirect()->back()->withErrors(['لا يمكنك حذف هذا الاذن ليس في حاله صحيحه']);
        }

        $type = null;
        if($licence->licensable_type == "App\Models\Doctor") {
            $type = 'doctors';
        } else {
            $type = 'facilities';
        }


        $licence->logs()->delete();
        $licence->delete();

        Log::create([
            'user_id' => Auth::id(),
            'branch_id' => Auth::user()->branch_id,
            'details' => "تم حذف الاذن مزاولة: معرف الاذن مزاولة {$licence->id}",
            'loggable_id' => $licence->licensable_id,
            'loggable_type' => Doctor::class,
            "action" => "delete_licence",
        ]);

        return redirect()->route(get_area_name().'.licences.index', ['type' => $type, 'status' => $licence->status])
            ->with('success', 'تم حذف الاذن مزاولة بنجاح.');
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
        Log::create([
            "user_id" => auth()->id(),
            "details" => " تمت طباعة اذن المزاولة " . $licence->id, 
            'loggable_id' => $licence->licensable_id,
            'loggable_type' => Doctor::class,
            "action" => "print_licence",
        ]);


        LicenceLog::create([
            "user_id" => auth()->id(),
            "details" => "تمت طباعة اذن المزاولة",
            "licence_id" => $licence->id,

        ]);

        return view('general.licences.print', compact('licence'));
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



            LicenceLog::create([
                "user_id" => auth()->id(),
                "licence_id" => $licence->id,
                "details" => "تم دفع  الاذن  " . ' وذلك بقيمة  ' . $licence->amount . 'دينار ليبي ',
            ]);



            $vault->increment('balance', $request->amount);
            $transaction = new Transaction();
            $transaction->user_id = auth()->id();
            $transaction->desc = "دفع رسوم تجديد العقد  " . $licence->id;
            $transaction->amount = $request->amount;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->transaction_type_id = 4;
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
