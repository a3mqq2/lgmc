<?php

namespace App\Http\Controllers\Common;

use App\Models\User;
use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Models\DoctorRank;
use App\Enums\InvoiceStatus;
use App\Mail\PaymentSuccess;
use Illuminate\Http\Request;
use App\Enums\MembershipStatus;
use App\Models\MedicalFacility;
use App\Models\TransactionType;
use App\Services\InvoiceService;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
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


            // if(auth()->user()->branch_id)
            // {
            //     $query->where('branch_id', auth()->user()->branch_id);
            // }
    
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
            })->get();

            return view('general.invoices.index', compact('invoices','users','vaults'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctor_ranks = DoctorRank::all();
        $transaction_types = TransactionType::all();
        return view('general.invoices.create', compact('doctor_ranks','transaction_types'));
    }

    /**
     * Store a newly created resource in storage.
     */
    /**
     * Store a newly created invoice in storage.
     */


     public function store(Request $request)
     {
         $request->validate([
             'description' => 'nullable|string|max:255',
             'amount' => 'nullable|numeric|min:0',
             'invoiceable_type' => 'nullable|string|in:App\\Models\\Doctor,App\\Models\\MedicalFacility',
             'invoiceable_id' => 'nullable|string',
             'transaction_type_id' => 'nullable|numeric',
             'ranks' => 'array',
             'from_years' => 'array',
             'to_years' => 'array',
         ]);
     

         $nextNumber = Invoice::count() + 1;
         $invoiceNumber = "INV-" . $nextNumber;
     
         $doctor = null;
         $medical_facility = null;
     
         if (!$request->type) {
             if ($request->invoiceable_type == "App\Models\Doctor") {
                 $doctor = Doctor::where('code', 'like',  $request->invoiceable_id )->first();
                 if (!$doctor) return back()->withErrors(['الطبيب غير موجود']);
             } elseif ($request->invoiceable_type == "App\Models\MedicalFacility") {
                 $medical_facility = MedicalFacility::find($request->invoiceable_id);
                 if (!$medical_facility) return back()->withErrors(['المنشأة الطبية غير موجودة']);
             }
         }
     
         $doctor = $doctor ?? Doctor::whereCode($request->invoiceable_id)->first();
     
         if ($request->type) {
             if (!$doctor) return back()->withInput()->withErrors(['الطبيب غير موجود']);
             if ($doctor->membership_status == MembershipStatus::Banned)
                 return back()->withInput()->withErrors(['لا يمكن إضافة قيمة فاتورة لطبيب محظور']);
             if ($doctor->membership_status == MembershipStatus::Active)
                 return back()->withInput()->withErrors(['لا يمكن إضافة قيمة فاتورة لطبيب مفعل']);
             if (!$doctor->type || !$doctor->doctor_rank_id)
                 return back()->withInput()->withErrors(['الصفة غير موجودة']);
     
             $price = Pricing::where('doctor_type', $doctor->type)
                             ->where('rank_id', $doctor->doctor_rank_id)
                             ->first();
     
             if (!$price) return back()->withInput()->withErrors(['لم يتم العثور على السعر المناسب']);
     
             $data = [
                 'invoice_number' => $invoiceNumber,
                 'description' => "قيمة تجديد العضوية للطبيب " . $doctor->name,
                 'amount' => $request->amount,
                 'status' => InvoiceStatus::unpaid,
                 'branch_id' => $doctor->branch_id ?? 1,
                 'user_id' => auth()->id(),
                 'invoiceable_type' => Doctor::class,
                 'invoiceable_id' => $doctor->id,
                 'pricing_id' => $price->id,
                 'transaction_type_id' => 1,
             ];
         } else {
             $amount = $request->amount;
             $description = $request->description;
     
             // حساب الفاتورة تلقائيًا إذا تم تفعيل حساب الأذونات
             if ($request->has('ranks') && $request->has('from_years') && $request->has('to_years')) {
                 $amount = 0;
                 foreach ($request->ranks as $index => $rankId) {
                     if (!$rankId || !isset($request->from_years[$index]) || !isset($request->to_years[$index])) continue;
                     $from = (int)$request->from_years[$index];
                     $to = (int)$request->to_years[$index];
                     $years = max(0, $to - $from + 1);
     
                     $pricing = Pricing::find($rankId);
                     if ($pricing) {
                         $amount += $pricing->amount * $years;
                     }
                 }
             }
     
             $data = [
                 'invoice_number' => $invoiceNumber,
                 'description' => $description,
                 'amount' => $amount,
                 'status' => InvoiceStatus::unpaid,
                 'branch_id' => $doctor?->branch_id ?? $medical_facility->branch_id ?? 1,
                 'user_id' => auth()->id(),
                 'invoiceable_type' => $request->invoiceable_type,
                 'invoiceable_id' => $doctor?->id ?? $medical_facility->id,
                 'transaction_type_id' => $request->transaction_type_id,
             ];
         }
     
         $invoice = Invoice::create($data);
     
         // إنشاء البنود إذا كانت موجودة
         if ($request->has('ranks') && $request->has('from_years') && $request->has('to_years')) {
             foreach ($request->ranks as $index => $rankId) {
                 if (!$rankId || !isset($request->from_years[$index]) || !isset($request->to_years[$index])) continue;
     
                 $from = (int)$request->from_years[$index];
                 $to = (int)$request->to_years[$index];
                 $years = max(0, $to - $from + 1);
     
                 $pricing = Pricing::find($rankId);
                 if ($pricing) {
                     $invoice->items()->create([
                         'pricing_id' => $rankId,
                         'from_year' => $from,
                         'to_year' => $to,
                         'amount' => $pricing->amount * $years,
                     ]);
                 }
             }
         }
     
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
    
            if ($invoice->status != InvoiceStatus::unpaid) {
                return redirect()->back()->withErrors(['لا يمكن الاستلام من هذه الفاتورة']);
            }
    
         
    
            $vault = auth()->user()->branch_id ? auth()->user()->branch->vault : Vault::first();
            $this->invoiceService->markAsPaid($vault, $invoice, $request->notes);
    
            $currentYear = now()->year;
            $hasCurrentYear = $invoice->items()->where(function ($query) use ($currentYear) {
                $query->where('from_year', '<=', $currentYear)
                      ->where('to_year', '>=', $currentYear);
            })->exists();
    
            if ($hasCurrentYear && $invoice->invoiceable_type === Doctor::class) {
                $doctor = $invoice->invoiceable;
                $doctor->membership_status = MembershipStatus::Active;
                $doctor->membership_expiration_date = now()->addYear()->format('Y-m-d');
                $doctor->save();
            }
    
            if ($invoice->doctorMail) {
                $mail = $invoice->doctorMail;
                $mail->status = 'under_proccess';
                $mail->save();
    
                if ($invoice->invoiceable->email) {
                    Mail::to($invoice->invoiceable->email)->send(new PaymentSuccess($mail));
                }
            }
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    
        return redirect()->route(get_area_name() . '.invoices.index')
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
