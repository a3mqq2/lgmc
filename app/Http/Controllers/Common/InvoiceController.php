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
            $query = Invoice::query();
    
    
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
    
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }


            $doctor = null;
            if($request->doctor_id)
            {
                $doctor = Doctor::find($request->doctor);
                $query->where("doctor_id", $request->doctor_id);
            }
 
    
            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
    
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }

            if($request->search)
            {
                $query->where('invoice_number', 'like', '%' . $request->search .'%');
            }

     
    
            $invoices = $query->orderBy('created_at', 'desc')->paginate(15)->appends($request->query());
            $users = User::orderByDesc('id')->get();
            $vaults = Vault::when(auth()->user()->branch_id, function($q) {
                $q->where('branch_id', auth()->user()->branch_id);
            })->get();

            return view('general.invoices.index', compact('invoices','users','vaults','doctor'));
    }
    

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $doctor_ranks = DoctorRank::all();
        return view('general.invoices.create', compact('doctor_ranks'));
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
             'description' => 'required|string|max:500',
             'amount' => 'nullable|numeric|min:0',
             'doctor_id' => 'required|exists:doctors,id',
             'ranks' => 'nullable|array',
             'from_years' => 'nullable|array',
             'to_years' => 'nullable|array',
             'ranks.*' => 'nullable|exists:doctor_ranks,id',
             'from_years.*' => 'nullable|integer|min:1900|max:2100',
             'to_years.*' => 'nullable|integer|min:1900|max:2100',
         ]);
     
         // الحصول على الطبيب
         $doctor = Doctor::findOrFail($request->doctor_id);
     
         // التحقق من حالة الطبيب
         if ($doctor->membership_status == MembershipStatus::Banned) {
             return back()->withInput()->withErrors(['لا يمكن إضافة فاتورة لطبيب محظور']);
         }
     
         // إنشاء رقم الفاتورة
         $nextNumber = Invoice::count() + 1;
         $invoiceNumber = "INV-" . str_pad($nextNumber, 6, '0', STR_PAD_LEFT);
     
         $amount = $request->amount;
         $description = $request->description;
     
         // حساب الفاتورة تلقائيًا إذا تم تفعيل حساب الاشتراكات السابقة
         if ($request->has('ranks') && $request->has('from_years') && $request->has('to_years')) {
             $calculatedAmount = 0;
             $hasValidItems = false;
     
             foreach ($request->ranks as $index => $rankId) {
                 // التحقق من وجود البيانات المطلوبة
                 if (!$rankId || !isset($request->from_years[$index]) || !isset($request->to_years[$index])) {
                     continue;
                 }
     
                 $from = (int)$request->from_years[$index];
                 $to = (int)$request->to_years[$index];
     
                 // التحقق من صحة السنوات
                 if ($from > $to) {
                     return back()->withInput()->withErrors(['سنة البداية يجب أن تكون أقل من أو تساوي سنة النهاية في البند رقم ' . ($index + 1)]);
                 }
     
                 $years = $to - $from + 1;
                 $hasValidItems = true;
     
                 // البحث عن السعر
                 $pricing = Pricing::where('doctor_rank_id', $rankId)
                                  ->where('doctor_type', $doctor->type->value)
                                  ->first();
     
                 if ($pricing) {
                     $calculatedAmount += $pricing->amount * $years;
                 } else {
                     // إذا لم يوجد سعر، استخدم سعر افتراضي أو أظهر خطأ
                     return back()->withInput()->withErrors(['لم يتم العثور على السعر المناسب للصفة في البند رقم ' . ($index + 1)]);
                 }
             }
     
             // إذا كان هناك بنود صحيحة، استخدم المبلغ المحسوب
             if ($hasValidItems) {
                 $amount = $calculatedAmount;
                 $description .= " (تم حساب المبلغ تلقائياً بناءً على الاشتراكات السابقة)";
             }
         }
     
         // التأكد من وجود مبلغ
         if (!$amount || $amount <= 0) {
             return back()->withInput()->withErrors(['يجب أن يكون مبلغ الفاتورة أكبر من صفر']);
         }
     
         try {
             // إنشاء الفاتورة
             $invoiceData = [
                 'invoice_number' => $invoiceNumber . ' - ' . $doctor->branch->code,
                 'description' => $description,
                 'amount' => $amount,
                 'status' => InvoiceStatus::unpaid,
                 'branch_id' => $doctor->branch_id,
                 'user_id' => auth()->id(),
                 'doctor_id' => $doctor->id,
                 'category' => 'registration'
             ];
     
             $invoice = Invoice::create($invoiceData);
     
             // إنشاء بنود الفاتورة إذا كانت موجودة
             if ($request->has('ranks') && $request->has('from_years') && $request->has('to_years')) {
                 foreach ($request->ranks as $index => $rankId) {
                     if (!$rankId || !isset($request->from_years[$index]) || !isset($request->to_years[$index])) {
                         continue;
                     }
     
                     $from = (int)$request->from_years[$index];
                     $to = (int)$request->to_years[$index];
                     $years = $to - $from + 1;
     
                     $pricing = Pricing::where('doctor_rank_id', $rankId)
                                      ->where('doctor_type', $doctor->type->value)
                                      ->first();
     

                     if ($pricing) {
                         $invoice->items()->create([
                             'pricing_id' => $pricing->id,
                             'from_year' => $from,
                             'to_year' => $to,
                             'amount' => $pricing->amount * $years,
                             'description' => "اشتراك " . $pricing->name . " من سنة {$from} إلى {$to} ({$years} سنة)",
                         ]);
                     }
                 }
             }
     
             // تسجيل العملية في السجل
             \Log::info('Manual dues invoice created', [
                 'invoice_id' => $invoice->id,
                 'invoice_number' => $invoice->invoice_number,
                 'doctor_id' => $doctor->id,
                 'doctor_name' => $doctor->name,
                 'amount' => $amount,
                 'user_id' => auth()->id(),
                 'has_items' => $invoice->items()->count() > 0
             ]);
     
             // في حالة الطلب عبر AJAX (المودال)
             if ($request->ajax()) {
                 return response()->json([
                     'success' => true,
                     'message' => 'تم إنشاء الفاتورة رقم ' . $invoice->invoice_number . ' بنجاح',
                     'invoice' => [
                         'id' => $invoice->id,
                         'invoice_number' => $invoice->invoice_number,
                         'amount' => number_format($invoice->amount, 2),
                         'description' => $invoice->description
                     ]
                 ]);
             }
     
             return redirect()->back()->with('success', 'تم إنشاء الفاتورة رقم ' . $invoice->id . ' بنجاح بمبلغ ' . number_format($amount, 2) . ' د.ل');
     
         } catch (\Exception $e) {
             \Log::error('Error creating manual dues invoice', [
                 'error' => $e->getMessage(),
                 'doctor_id' => $doctor->id,
                 'user_id' => auth()->id(),
                 'trace' => $e->getTraceAsString()
             ]);
     
             if ($request->ajax()) {
                 return response()->json([
                     'success' => false,
                     'message' => 'حدث خطأ أثناء إنشاء الفاتورة'
                 ], 500);
             }
     
             return back()->withInput()->withErrors(['حدث خطأ أثناء إنشاء الفاتورة، يرجى المحاولة مرة أخرى']);
         }
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

    
         
    
            $vault = auth()->user()->vault;
            $this->invoiceService->markAsPaid($vault, $invoice, $request->notes);
    
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withErrors([$e->getMessage()]);
        }
    
        return redirect()->back()->with('success', 'تم تحديث حالة الفاتورة إلى مدفوعة.');
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

    public function print_list(Request $request)
    {
        $query = Invoice::query();
        if($request->doctor_id)
        {
            $query->where('doctor_id', $request->doctor_id);
        }

        $invoices = $query->orderByDesc('id')->get();
        return view('general.invoices.print_list', compact('invoices'));
    }
}
