<?php

namespace App\Http\Controllers\Common;

use App\Models\User;
use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use App\Enums\MembershipStatus;
use App\Models\MedicalFacility;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Mail\PaymentSuccess;
use Illuminate\Support\Facades\Mail;

class InvoiceController extends Controller
{

    protected $invoiceService;

    public function __construct(InvoiceService $invoiceService)
    {
        $this->invoiceService = $invoiceService;
    }


        public function index(Request $request)
        {
            // Start the query
            $query = Invoice::query();
    
            // Filter by invoiceable type (Doctor or MedicalFacility)
            if ($request->filled('invoiceable')) {
                $invoiceableType = $request->invoiceable === 'Doctor' ? 'App\Models\Doctor' : 'App\Models\MedicalFacility';
                $query->where('invoiceable_type', $invoiceableType);
            }
    
            // Filter by status (paid, unpaid, partial)
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
    
            // Filter by user_id (who created the invoice)
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
    
            // Filter by license_id
            if ($request->filled('license_id')) {
                $query->where('license_id', $request->license_id);
            }
    
            // Filter by date range
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
    
            // Filter by amount range
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }



            if($request->filled('type'))
            {
                $query->where('invoiceable_type', $request->type);
            } 


            if(auth()->user()->branch_id)
            {
                $query->where('branch_id', auth()->user()->branch_id);
            }
    
            // Search by invoice_number or description
            if ($request->filled('search')) {
                $query->where(function ($subQuery) use ($request) {
                    $subQuery->where('invoice_number', 'like', '%' . $request->search . '%')
                             ->orWhere('description', 'like', '%' . $request->search . '%');
                });
            }
    
            // Paginate results
            $invoices = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
            $users = User::orderByDesc('id')->get();
            $vaults = Vault::when(auth()->user()->branch_id, function($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->when(!auth()->user()->branch_id, function($q) {
                $q->whereNull('branch_id');
            })->get();
            return view('general.invoices.index', compact('invoices','users','vaults'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('general.invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        // 1. Validate incoming data
        $request->validate([
            'description' => 'nullable|string|max:255',
            'amount'      => 'nullable|numeric|min:0',
            'invoiceable_type' => 'nullable|string|in:App\\Models\\Doctor,App\\Models\\MedicalFacility',
            'invoiceable_id'   => 'nullable|string',
            'transaction_type_id' => 'nullable|numeric',
        ]);



        // 2. Generate an invoice number like "INV-xxx"
        $nextNumber = Invoice::count() + 1; 
        $invoiceNumber = "INV-" . $nextNumber;


        // if doctor search by code

        if(!$request->type)
        {
            if($request->invoiceable_type == "App\Models\Doctor")
            {
                $doctor = Doctor::where('code', $request->invoiceable_id)->first();
                if(!$doctor)
                {
                    return redirect()->back()->withErrors(['الطبيب غير موجود']);
                }

                if($doctor->branch_id != auth()->user()->branch_id)
                {
                    return redirect()->back()->withErrors(['لا يمكن إضافة قيمة فاتورة لطبيب ليس من فرعك']);
                }
            } elseif($request->invoiceable_type == "App\Models\MedicalFacility") {
                $medical_facility = MedicalFacility::find($request->invoiceable_id);
                if(!$medical_facility)
                {
                    return redirect()->back()->withErrors(['المنشأة الطبية غير موجودة']);
                }

                if($medical_facility->branch_id != auth()->user()->branch_id)
                {
                    return redirect()->back()->withErrors(['لا يمكن إضافة قيمة فاتورة لمنشأة طبية ليست من فرعك']);
                }
            }
        }



        $doctor = Doctor::whereCode($request->invoiceable_id)->first();

        if($request->type)
        {

            if(!$doctor)
            {
                return redirect()->back()->withInput()->withErrors(['الطبيب غير موجود']);
            }

            if($doctor->membership_status == MembershipStatus::Banned)
            {
                return redirect()->back()->withInput()->withErrors(['لا يمكن إضافة قيمة فاتورة لطبيب محظور']);
            }



            if($doctor->membership_status == MembershipStatus::Active)
            {
                return redirect()->back()->withInput()->withErrors(['لا يمكن إضافة قيمة فاتورة لطبيب مفعل']);
            }
           

            if(!$doctor->type || !$doctor->doctor_rank_id)
            {
                return redirect()->back()->withInput()->withErrors(['لا يمكن اضافة قيمة فاتورة لطبيب بدون تحديد صفه الطبيب']);
            }


            if($doctor->type ==  DoctorType::Libyan)
            {
                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(1);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(2);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(3);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(4);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(5);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(6);  
                    }

            } else if($doctor->type == DoctorType::Foreign)
            {
                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(13);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(14);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(15);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(16);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(17);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(18);  
                    }
            } else if($doctor->type == DoctorType::Visitor) {
                    if($doctor->doctor_rank_id == 3 || $doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(25);
                    }


                    if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(26);
                    }


                    if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(27);
                    }


                    if(!$price)
                    {
                        return redirect()->back()->withInput()->withErrors(['لا يمكن اضافة طبيب زائر بدون تحديد الالصفة الصحيحة']);
                    }

                    
            } else if($doctor->type == DoctorType::Palestinian) {

                    if($doctor->doctor_rank_id == 1)
                    {
                        $price = Pricing::find(53);
                    } else if($doctor->doctor_rank_id == 2) 
                    {
                        $price = Pricing::find(54);
                    } else if($doctor->doctor_rank_id == 3)
                    {
                        $price = Pricing::find(55);
                    } else if($doctor->doctor_rank_id == 4)
                    {
                        $price = Pricing::find(56);
                    } else if($doctor->doctor_rank_id == 5)
                    {
                        $price = Pricing::find(57);  
                    }else if($doctor->doctor_rank_id == 6)
                    {
                        $price = Pricing::find(58);  
                    }

            } else {
                    \Log::error('Doctor Type is not exists to create an register invoice');
            }


            $data = [
                'invoice_number' => $invoiceNumber,
                'description'    => "قيمة تجديد العضوية للطبيب " . $doctor->name,
                'amount'         => $request->amount,
                'status'         => InvoiceStatus::unpaid,
                'branch_id'      => $doctor->branch_id,
                'user_id'        => auth()->id(),
                'invoiceable_type' => 'App\Models\Doctor',
                'pricing_id' => $price->id,
                'invoiceable_id'   => $doctor->id,
                'transaction_type_id' => 1,
            ];
        } else {
            $data = [
                'invoice_number' => $invoiceNumber,
                'description'    => $request->description,
                'amount'         => $request->amount,
                'status'         => InvoiceStatus::unpaid,
                'branch_id'      => isset($doctor) ? $doctor->branch_id : $medical_facility->branch_id,
                'user_id'        => auth()->id(),
                'invoiceable_type' => $request->invoiceable_type,
                'invoiceable_id'   => $doctor->id,
                'transaction_type_id' => $request->transaction_type_id,
            ];
        }



        // 4. Create the invoice
        $invoice = Invoice::create($data);

        // 5. Redirect with a success message
        return redirect()->route(get_area_name() . '.invoices.index')
            ->with('success', 'تم إنشاء الفاتورة رقم ' . $invoice->invoice_number . ' بنجاح.');
    }


    /**
     * Display the specified resource.
     */
    public function show(Invoice $invoice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice $invoice)
    {
        return view('general.invoices.edit', compact('invoice'));
    }


    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'amount' => $request->amount,
        ]);

        return redirect()->route(get_area_name().'.invoices.index')->with('success', 'تم تحديث الفاتورة بنجاح.');
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice $invoice)
    {

        // add conditions
        if($invoice->status != InvoiceStatus::unpaid)
        {
            return redirect()->back()
            ->withErrors(['لا يمكن حذف هذه الفاتورة']);
        }

        if($invoice->branch_id != auth()->user()->branch_id)
        {
            return redirect()->back()->withErrors(['لا يمكن حذف قيمة فاتورة لغير فرعها الحالي']);
        }


        // if the invoice paid you cannot delete it 

        if($invoice->status != InvoiceStatus::unpaid)
        {
            return redirect()->back()->withErrors(['لا يمكن حذف فاتورة مدفوعة']);
        }


        $invoice->delete();
        return redirect()->route(get_area_name().'.invoices.index')->with('success', 'تم حذف الفاتورة بنجاح.');
    }


    public function received(Invoice $invoice, Request $request)
    {

        try {
            DB::beginTransaction();
            if($invoice->status != InvoiceStatus::unpaid)
            {
                return redirect()->back()
                ->withErrors(['لا يمكن الاستلام من هذه الفاتورة']);
            }
    


    
            if($invoice->branch_id != auth()->user()->branch_id)
            {
                return redirect()->back()->withErrors(['لا يمكن استلام قيمة فاتورة لغير فرعها الحالي']);
            }
            $vault = auth()->user()->branch_id ? auth()->user()->branch->vault : Vault::first();
            $this->invoiceService->markAsPaid($vault,$invoice, $request->notes);


            // check the invoice have doctor email 

            if($invoice->doctorMail)
            {
                $mail = $invoice->doctorMail;
                $mail->status = 'under_proccess';
                $mail->save();


                if($invoice->invoiceable->email)
                {
                    Mail::to($invoice->invoiceable->email)->send(new PaymentSuccess($mail));
                }

            }

            DB::commit();
        } catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->route(get_area_name().'.invoices.index')
            ->with('success', 'تم تحديث حالة الفاتورة إلى مدفوعة.');
    }

    public function relief(Request $request, Invoice $invoice)
    {
        try {
            DB::beginTransaction();
            if($invoice->status != InvoiceStatus::unpaid)
            {
                return redirect()->back()
                ->withErrors(['لا يمكن الإعفاء من هذه الفاتورة']);
            }
    
            if($invoice->branch_id != auth()->user()->branch_id)
            {
                return redirect()->back()->withErrors(['لا يمكن إعفاء قيمة فاتورة لغير فرعها الحالي']);
            }
            $this->invoiceService->markAsRelief($invoice, $request->notes);
            DB::commit();
        } catch(\Exception $e)
        {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }

        return redirect()->route(get_area_name().'.invoices.index')
            ->with('success', 'تم تحديث حالة الفاتورة إلى معفاة.');
    }


    public function print(Invoice $invoice)
    {
        return view('general.invoices.print', compact('invoice'));
    }
}
