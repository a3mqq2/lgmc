<?php

namespace App\Http\Controllers\Common;

use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\DoctorEmail;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\DoctorEmailRequest;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DoctorEmailController extends Controller
{
    public function index(Request $request)
    {
        $query = DoctorEmail::with(['doctor', 'requests.pricing'])
            ->when($request->doctor_id, fn($q) => $q->where('doctor_id', $request->doctor_id))
            ->when($request->email, fn($q) => $q->where('email', 'like', "%{$request->email}%"))
            ->when($request->country_id, fn($q) => $q->where('country_id', $request->country_id));

        return view('general.doctor_emails.index', [
            'emails' => $query->latest()->paginate(50),
            'doctors' => Doctor::latest()->get(),
            'countries' => Country::all(),
            'pricings' => Pricing::where('type', 'service')->where('is_local',false)->get(),
        ]);
    }

    public function create()
    {
        return view('general.doctor_emails.create', [
            'doctors' => Doctor::where('membership_status','active')->latest()->get(),
            'countries' => Country::all(),
            'pricings' => Pricing::where('type', 'service')->where('doctor_type', request('doctor_type'))->where('is_local', false)->get(),
            'doctor_type' => request('doctor_type'),
        ]);
    }

    
    // ...
    
    public function store(Request $request)
    {
        $request->validate([
            'doctor_id'                    => ['required', Rule::exists('doctors','id')],
            'emails'                       => ['required','array','min:1'],
    
            'emails.*.email'               => ['required','email'],
            'emails.*.country_id'          => ['nullable', Rule::exists('countries','id')],
            'emails.*.has_docs'            => ['nullable','boolean'],
            'emails.*.last_year'           => ['nullable','digits:4','integer','min:2000','max:'.now()->year],
    
            'emails.*.requests'            => ['required','array','min:1'],
            'emails.*.requests.*'          => [Rule::exists('pricings','id')],
    
            'emails.*.files'               => ['array'],
            'emails.*.files.*'             => ['nullable','mimes:pdf,jpg,jpeg,png'],
        ]);
    
        $doctor = Doctor::findOrFail($request->doctor_id);
    
        DB::transaction(function () use ($request, $doctor) {
    
            foreach ($request->emails as $emailData) {
    
                /* 1. إنشاء البريد */
                $email = DoctorEmail::create([
                    'doctor_id'  => $doctor->id,
                    'branch_id'  => $doctor->branch_id,
                    'email'      => $emailData['email'],
                    'country_id' => $emailData['country_id'] ?? null,
                    'has_docs'   => !empty($emailData['has_docs']),
                    'last_year'  => $emailData['last_year'] ?? null,
                ]);
    
                /* 2. الطلبات + حساب الإجمالي */
                $total = 0;  $requestIDs = [];
    
                foreach ($emailData['requests'] as $idx => $pricingId) {
    
                    $filePath = null;
                    if (!empty($emailData['files'][$idx])) {
                        $filePath = $emailData['files'][$idx]
                                    ->store('doctor_email_requests','public');
                    }
    
                    $pricing   = Pricing::find($pricingId);
                    $total    += $pricing->amount;
    
                    $req = DoctorEmailRequest::create([
                        'doctor_email_id' => $email->id,
                        'pricing_id'      => $pricingId,
                        'file_path'       => $filePath,
                        'status'          => 'pending',
                    ]);
    
                    $requestIDs[] = $req->id;
                }
    
                /* 3. إنشاء فاتورة واحدة بالإجمالي */
                $invoice = Invoice::create([
                    'invoice_number'   => 'EML-'.$email->id,
                    'invoiceable_id'   => $doctor->id,
                    'invoiceable_type' => Doctor::class,
                    'description'      => 'رسوم طلبات بريدية للطبيب',
                    'user_id'          => auth()->id(),
                    'amount'           => $total,
                    'pricing_id'       => null,
                    'status'           => 'unpaid',
                    'branch_id'        => $doctor->branch_id,
                ]);
    
                /* 4. ربط الطلبات بالفاتورة */
                DoctorEmailRequest::whereIn('id',$requestIDs)
                                  ->update(['invoice_id'=>$invoice->id]);
            }
        });
    
        return redirect()->route(get_area_name().'.doctor-emails.index')
                         ->with('success','تم حفظ البيانات وإنشاء الفواتير بنجاح.');
    }
    

    public function show(DoctorEmail $doctorEmail)
    {
        return view('general.doctor_emails.show', [
            'email' => $doctorEmail->load('doctor', 'requests.pricing'),
        ]);
    }

    public function edit(DoctorEmail $doctorEmail)
    {
        return view('general.doctor_emails.edit', [
            'email' => $doctorEmail->load('requests'),
            'countries' => Country::all(),
            'pricings' => Pricing::where('type', 'service')->get(),
        ]);
    }

    public function update(Request $request, DoctorEmail $doctorEmail)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'country_id' => ['nullable', Rule::exists('countries', 'id')],
        ]);

        $doctorEmail->update([
            'email' => $request->email,
            'country_id' => $request->country_id,
        ]);

        return redirect()->route(get_area_name().'.doctor-emails.show', $doctorEmail)->with('success', 'تم التحديث بنجاح.');
    }

    public function destroy(DoctorEmail $doctorEmail)
    {
        $doctorEmail->delete();
        return redirect()->route(get_area_name().'.doctor-emails.index')->with('success', 'تم الحذف بنجاح.');
    }
}
