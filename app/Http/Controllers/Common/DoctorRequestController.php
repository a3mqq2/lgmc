<?php

namespace App\Http\Controllers\Common;

use App\Models\Vault;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use Illuminate\Support\Str;
use App\Enums\InvoiceStatus;
use App\Enums\RequestStatus;
use Illuminate\Http\Request;
use App\Models\DoctorRequest;
use App\Services\VaultService;
use App\Services\InvoiceService;
use App\Http\Controllers\Controller;
use App\Models\Log;
class DoctorRequestController extends Controller
{


    public $vaultService;
    public $invoiceService;
    public  $vault;
    public function __construct(VaultService $vaultService, InvoiceService $invoiceService)
    {
        $this->vaultService = $vaultService;
        $this->invoiceService = $invoiceService;
    }

    /**
     * Display a listing of the resource.
     */


     public function getVault()
     {
        return auth()->user()->branch;
     }

    public function index(Request $request)
    {
        $query  = DoctorRequest::query();

        if(request("doctor_type"))
        {
            $query->where("doctor_type", request("doctor_type"));
        }

        if(request("status"))
        {
            $query->where("status", request("status"));
        }

        if(request('doctor_name'))
        {
            $query->whereHas('doctor', function($q) {
                $q->where('name', 'like', '%' . request('doctor_name') . '%');
            });
        }

        if(auth()->user()->branch_id)
        {
            $query->where('branch_id', auth()->user()->branch_id);
        }   


        return view('general.doctor_requests.index', [
            'doctorRequests' => $query->paginate(50),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $request->validate([
            "doctor_type" => "required",
        ]);

        $pricings = Pricing::where('doctor_type', $request->doctor_type)->where('type', 'service' )->where('is_local', true)->get();
        $doctors = Doctor::latest()->where('type', $request->doctor_type)->when(auth()->user()->branch_id, function($q) {
            $q->where('branch_id', auth()->user()->branch_id);
        })->get();
        return view('general.doctor_requests.create', [
            'pricings' => $pricings,
            'doctor_type' => $request->doctor_type,
            'doctors' => $doctors,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'pricing_id' => 'required|exists:pricings,id',
            'date' => 'required|date',
            'notes' => 'nullable|string|max:1000',
            'doctor_type' => 'required|in:libyan,foreign,palestinian,visitor',
        ], [
            'doctor_id.required' => 'حقل اسم الطبيب مطلوب.',
            'doctor_id.exists' => 'الطبيب المحدد غير موجود.',
            'pricing_id.required' => 'حقل نوع الطلب مطلوب.',
            'pricing_id.exists' => 'نوع الطلب المحدد غير موجود.',
            'date.required' => 'حقل تاريخ الطلب مطلوب.',
            'date.date' => 'تاريخ الطلب يجب أن يكون تاريخاً صالحاً.',
            'notes.string' => 'حقل الملاحظات يجب أن يكون نصاً.',
            'notes.max' => 'حقل الملاحظات لا يجب أن يتجاوز 1000 حرف.',
            'doctor_type.required' => 'نوع الطبيب مطلوب.',
            'doctor_type.in' => 'نوع الطبيب المحدد غير صالح.',
        ]);
    
        try {
            $pricing = Pricing::findOrFail($request->pricing_id);
            $doctor = Doctor::findOrFail($request->doctor_id);
    


            if ($doctor->type->value !== $request->doctor_type) {
                return redirect()->back()->withInput()->withErrors([
                    'doctor_id' => 'نوع الطبيب لا يتطابق مع نوع الطلب المحدد.',
                ]);
            }
    
            $doctorRequest = new DoctorRequest();
            $doctorRequest->doctor_id = $request->doctor_id;
            $doctorRequest->pricing_id = $request->pricing_id;
            $doctorRequest->date = $request->date;
            $doctorRequest->notes = $request->notes;
            $doctorRequest->status = RequestStatus::under_process;
            $doctorRequest->approved_at = now();
            $doctorRequest->approved_by = auth()->id();
            $doctorRequest->branch_id = auth()->user()->branch_id ?? null; 
            $doctorRequest->doctor_type = $request->doctor_type;
            $doctorRequest->save();
    
            Log::create([
                'user_id' => auth()->id(),
                'loggable_id' => $doctorRequest->doctor->id,
                'loggable_type' => Doctor::class,
                'details' => "تمت الموافقة على طلب خدمة للطبيب: {$doctorRequest->doctor->name} برقم الطلب: {$doctorRequest->id}",
                "action" => "create_doctor_request",
            ]);

            $this->createInvoice($doctorRequest);


            return redirect()
                ->route(get_area_name() . '.doctor-requests.index', ['doctor_type' => $request->doctor_type])
                ->with('success', 'تم إضافة الطلب بنجاح.');
        } catch (\Exception $e) {
            // Log error for debugging
            \Log::error('Error storing doctor request: ' . $e->getMessage());
    
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['حدث خطأ أثناء إضافة الطلب. يرجى المحاولة لاحقاً.']);
        }
    }
    

    /**
     * Display the specified resource.
     */
    public function show(DoctorRequest $doctorRequest)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DoctorRequest $doctorRequest)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DoctorRequest $doctorRequest)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DoctorRequest $doctorRequest)
    {
        //
    }


    public function approve(Request $request, DoctorRequest $doctorRequest)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $doctorRequest->update([
                'status' => \App\Enums\RequestStatus::under_process,
                'approved_by' => auth()->id(),
                'approved_at' => now(),
                'notes' => $request->notes,
            ]);


            $this->createInvoice($doctorRequest);

            return redirect()
                ->route(get_area_name() . '.doctor-requests.index', ['doctor_type' => $doctorRequest->doctor_type])
                ->with('success',  'تمت الموافقة على الطلب بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Error approving doctor request: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['حدث خطأ أثناء الموافقة على الطلب. يرجى المحاولة لاحقاً.']);
        }
    }

    public function reject(Request $request, DoctorRequest $doctorRequest)
    {
        $request->validate([
            'reason' => 'required|string|max:1000',
            'notes' => 'nullable|string|max:1000',
        ]);

        try {
            $doctorRequest->update([
                'status' => \App\Enums\RequestStatus::rejected,
                'rejected_by' => auth()->id(),
                'rejected_at' => now(),
                'notes' => $request->notes,
            ]);


            Log::create([
                'user_id' => auth()->id(),
                'loggable_id' => $doctorRequest->doctor->id,
                'loggable_type' => Doctor::class,
                'details' => "تم رفض طلب خدمة للطبيب: {$doctorRequest->doctor->name} برقم الطلب: {$doctorRequest->id} - السبب: {$request->reason}",
                "action" => "reject_doctor_request",
            ]);


            return redirect()
                ->route(get_area_name() . '.doctor-requests.index', ['doctor_type' => $doctorRequest->doctor_type])
                ->with('success', 'تم رفض الطلب بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Error rejecting doctor request: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['حدث خطأ أثناء رفض الطلب. يرجى المحاولة لاحقاً.']);
        }
    }


    public function done(Request $request, DoctorRequest $doctorRequest)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        try {


            if($doctorRequest->invoice->status == InvoiceStatus::unpaid)
            {
                return redirect()->back()->withErrors(['لا يمكن اكمال الطلب دون دفع قيمة الفاتورة لدى المالية']);
            }

            if ($doctorRequest->status !== \App\Enums\RequestStatus::under_process) {
                return redirect()
                    ->back()
                    ->withErrors(['لا يمكن إكمال الطلب إذا لم يكن في حالة "قيد التجهيز".']);
            }


         


            Log::create([
                'user_id' => auth()->id(),
                'loggable_id' => $doctorRequest->doctor->id,
                'loggable_type' => Doctor::class,
                'details' => "تم إكمال طلب خدمة للطبيب: {$doctorRequest->doctor->name} برقم الطلب: {$doctorRequest->id}",
                "action" => "done_doctor_request",
            ]);


            $doctorRequest->update([
                'status' => \App\Enums\RequestStatus::done,
                'done_by' => auth()->id(),
                'done_at' => now(),
                'notes' => $request->notes,
            ]);

            return redirect()
                ->route(get_area_name() . '.doctor-requests.index', ['doctor_type' => $doctorRequest->doctor_type])
                ->with('success', 'تم إكمال الطلب بنجاح.');
        } catch (\Exception $e) {
            \Log::error('Error completing doctor request: ' . $e->getMessage());

            return redirect()
                ->back()
                ->withErrors(['حدث خطأ أثناء إكمال الطلب. يرجى المحاولة لاحقاً.']);
        }
    }


    public function createInvoice($doctorRequest)
    {

       
        $data = [
            'invoice_number' => "REQ-" . $doctorRequest->id,
            'invoiceable_id' => $doctorRequest->doctor_id,
            'invoiceable_type' => 'App\Models\Doctor',
            'description' => "رسوم خدمة للطبيب : " . $doctorRequest->pricing->name ,
            'user_id' => auth()->id(),
            'amount' => $doctorRequest->pricing->amount,
            'pricing_id' => $doctorRequest->pricing_id,
            'status' => 'unpaid',
            'branch_id' => $doctorRequest->doctor->branch_id,
            'user_id' => auth()->user()->id,
        ];


        $invoice = Invoice::create($data);
        $doctorRequest->invoice_id = $invoice->id;
        $doctorRequest->save();

        // $this->invoiceService->markAsPaid($this->vault, $invoice, $invoice->description);


        Log::create([
            'user_id' => auth()->id(),
            'loggable_id' => $doctorRequest->doctor->id,
            'loggable_type' => Doctor::class,
            'details' => "تم إنشاء فاتورة للطبيب: {$doctorRequest->doctor->name} بمبلغ: {$doctorRequest->pricing->amount} - رقم الفاتورة: REQ-{$doctorRequest->id}",
            "action" => "create_invoice",
        ]);
        
    }


    public function print(DoctorRequest $doctorRequest)
    {
        return view('general.doctor_requests.print', compact('doctorRequest'));
    }
}
