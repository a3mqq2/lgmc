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
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;

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
        return auth()->user()->branch ? auth()->user()->branch->vault : Vault::first();
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

        if(request('branch_id'))
        {
            $query->where('branch_id', request('branch_id'));
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

        $pricings = Pricing::where('doctor_type', $request->doctor_type)->where('type', 'service' )->get();
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
                    ->withErrors(['لا يمكن إكمال الطلب إذا لم يكن في حالة "قيد المعالجة".']);
            }


            if(env('APP_ENV') == "production")
            {
                if(in_array($doctorRequest->pricing_id, [72,48,38]))
                {

                    $email = $doctorRequest->doctor->email;
                    $password = Str::random(9);

                    $cpanel = new \GuzzleHttp\Client();
                    $cpanelResponse = $cpanel->post('https://your-cpanel-url:2083/execute/Email/add_pop', [
                        'headers' => [
                            'Authorization' => 'Basic ' . base64_encode('username:password'), 
                        ],
                        'form_params' => [
                            'email' => $email,
                            'domain' => 'lgmc.ly',
                            'password' => $password,
                            'quota' => 1024, 
                        ],
                    ]);

                    $responseBody = json_decode($cpanelResponse->getBody(), true);
                    if ($responseBody['status'] !== 1) {
                        throw new \Exception('Failed to create email: ' . ($responseBody['errors'][0] ?? 'Unknown error'));
                    }
                }
            }

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
            'branch_id' => auth()->user()->branch_id,
        ];


        $invoice = Invoice::create($data);
        $doctorRequest->invoice_id = $invoice->id;
        $doctorRequest->save();

        // $this->invoiceService->markAsPaid($this->vault, $invoice, $invoice->description);

    }


    public function print(DoctorRequest $doctorRequest)
    {
        return view('general.doctor_requests.print', compact('doctorRequest'));
    }
}
