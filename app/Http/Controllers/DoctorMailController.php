<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Models\DoctorMail;
use App\Mail\CompletedMail;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use App\Models\DoctorMailItem;
use Illuminate\Support\Facades\DB;
use App\Mail\RequestPendingPayment;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestUnderModification;
use Illuminate\Support\Facades\Storage;

class DoctorMailController extends Controller
{
    /* ===============================================================
     * الفهرس
     * =============================================================== */
    public function index(Request $request)
    {
        $doctor_mails = DoctorMail::with(['doctor','doctorMailItems.pricing'])
            ->when($request->filled('name'),  fn($q)=>$q->whereHas('doctor',fn($qq)=>$qq->where('name','like',"%{$request->name}%")))
            ->when($request->filled('code'),  fn($q)=>$q->whereHas('doctor',fn($qq)=>$qq->where('code',$request->code)))
            ->when($request->filled('request_number'), fn($q)=>$q->where('id',$request->request_number))
            ->when($request->filled('request_date'),   fn($q)=>$q->whereDate('created_at',$request->request_date))
            ->when($request->filled('status'), fn($q)=>$q->where('status',$request->status))
            ->latest()->paginate(30);

        $unitMap  = ['libyan'=>85,'palestinian'=>86,'foreign'=>87];
        $unitCash = [];                      // cache للأسعار

        foreach ($doctor_mails as $mail) {
            $mail->country_names = Country::whereIn('id',$mail->countries)->pluck('name')->implode(', ');

            /* grand_total مُسجَّل مسبقًا؟ */
            if ($mail->grand_total > 0) continue;

            $type = $mail->doctor->type instanceof DoctorType
                    ? $mail->doctor->type->value
                    : (string) $mail->doctor->type;

            /* جلب أو كاش السعر */
            if (! isset($unitCash[$type])) {
                $pid = $unitMap[$type] ?? null;
                $unitCash[$type] = $pid ? Pricing::find($pid)?->amount ?? 0 : 0;
            }
            $unitPrice   = $unitCash[$type];
            $emailTotal  = $unitPrice * count($mail->emails);
            $servicesSum = $mail->doctorMailItems->sum(fn($i)=>$i->pricing->amount);

            $mail->grand_total = $emailTotal + $servicesSum;
        }

        return view('general.doctor_mails.index', compact('doctor_mails'));
    }

    /* =============================================================== */
    public function create()   { return view('general.doctor_mails.create'); }

    /* ===============================================================
     * تخزين طلب جديد
     * =============================================================== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id'            => ['required','exists:doctors,id'],
            'emails'               => ['required','array','min:1'],
            'emails.*'             => ['required','email'],
            'countries'            => ['nullable','array'],
            'countries.*'          => ['required'],
            'notes'                => ['nullable','string'],
            'extracted_before'     => ['boolean'],
            'services'             => ['required','array','min:1'],
            'services.*.id'        => ['required','exists:pricings,id'],
            'services.*.file'      => ['nullable','file'],
            'services.*.amount'    => ['nullable','numeric'],
            'services.*.work_mention'=> ['nullable','in:with,without'],
            'last_extract_year'    => ['nullable','integer','digits:4'],
        ]);

        // Handle new/existing emails: create Email records and collect addresses
        $emailsList = [];
        foreach ($data['emails'] as $email) {
            $record = Email::firstOrCreate(['email' => $email]);
            $emailsList[] = $record->email;
        }

        // Handle new/existing countries: translate "new_…" entries into Country IDs
        $countriesList = [];
        if (! empty($data['countries'])) {
            foreach ($data['countries'] as $c) {
                if (is_string($c) && str_starts_with($c, 'new_')) {
                    $name = substr($c, 4);
                    $country = Country::firstOrCreate(['name' => $name]);
                    $countriesList[] = $country->id;
                } else {
                    // assume integer ID
                    $countriesList[] = $c;
                }
            }
        }

        DB::beginTransaction();
        try {
            // حساب الأسعار
            $doctor = Doctor::findOrFail($data['doctor_id']);
            $typeKey = $doctor->type instanceof DoctorType
                     ? $doctor->type->value
                     : (string) $doctor->type;
            $unitMap   = ['libyan'=>85,'palestinian'=>86,'foreign'=>87];
            $unitPrice = $unitMap[$typeKey] ?? 0;
            $unitPrice = $unitPrice
                       ? (Pricing::find($unitMap[$typeKey])->amount ?? 0)
                       : 0;

            $emailsTotal = $unitPrice * count($emailsList);
            $servicesTotal = collect($data['services'])->reduce(function($sum, $srv) {
                return $sum + (
                    isset($srv['amount'])
                        ? $srv['amount']
                        : (Pricing::find($srv['id'])->amount ?? 0)
                );
            }, 0);
            $grandTotal = $emailsTotal + $servicesTotal;

            // حفظ الطلب
            $mail = DoctorMail::create([
                'doctor_id'         => $data['doctor_id'],
                'contacted_before'  => $data['extracted_before'] ?? false,
                'status'            => 'under_approve',
                'notes'             => $data['notes'] ?? null,
                'countries'         => $countriesList,
                'emails'            => $emailsList,
                'grand_total'       => $grandTotal,
                'last_extract_year' => $data['last_extract_year'] ?? null,
            ]);

            // حفظ العناصر
            foreach ($data['services'] as $item) {
                $path = $item['file']?->store('doctor_mail_items', 'public') ?: null;
                DoctorMailItem::create([
                    'doctor_mail_id' => $mail->id,
                    'pricing_id'     => $item['id'],
                    'status'         => 'pending',
                    'file'           => $path,
                    'work_mention'   => $item['work_mention'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()
                ->route(get_area_name() . '.doctor-mails.index')
                ->with('success', 'تم إنشاء الطلب بنجاح');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error($e);
            return back()->withErrors('حدث خطأ أثناء المعالجة');
        }
    }

    /* =============================================================== */
    public function show(DoctorMail $doctorMail)
    {
        $doctorMail->load('doctor','doctorMailItems.pricing');
        $doctorMail->country_names = Country::whereIn('id',$doctorMail->countries)->pluck('name')->implode(', ');
        return view('general.doctor_mails.show', compact('doctorMail'));
    }

    public function edit(DoctorMail $doctorMail)
    {
        $doctorMail->load('doctor','doctorMailItems.pricing');
        return view('general.doctor_mails.edit', compact('doctorMail'));
    }

    /* ===============================================================
     * اعتماد / تعديل
     * =============================================================== */
    public function update(Request $request, DoctorMail $doctorMail)
    {
        if($doctorMail->status!=='under_approve'){
            return back()->withErrors('لا يمكنك تعديل هذا الطلب في هذه الحالة.');
        }

        $data = $request->validate([
            'items'            => ['required','array'],
            'items.*.approved' => ['required','in:0,1'],
            'items.*.reason'   => ['nullable','string'],
        ]);

        DB::beginTransaction();
        try{
            $anyRejected=false;
            foreach($data['items'] as $id=>$attrs){
                $approved=$attrs['approved']==='1';
                $reason  =$approved?null:$attrs['reason'];
                if(!$approved && empty($reason)) throw new \Exception('يجب إدخال سبب لكل بند مرفوض');

                $item=$doctorMail->doctorMailItems()->findOrFail($id);
                $item->update([
                    'status'          => $approved?'approved':'rejected',
                    'rejected_reason' => $reason
                ]);
                if(!$approved) $anyRejected=true;
            }

            if($anyRejected){
                $doctorMail->update(['status'=>'under_edit']);

                if($doctorMail->doctor->email)
                {
                      Mail::to($doctorMail->doctor->email)->send(new RequestUnderModification($doctorMail));
                }

            }else{
                $doctorMail->update(['status'=>'under_payment']);

                Invoice::create([
                    'invoice_number'   => 'DM-'.date('Y').'-'.$doctorMail->id . '-'.str_pad($doctorMail->id, 5, '0', STR_PAD_LEFT),
                    'doctor_mail_id'   => $doctorMail->id,
                    'invoiceable_type' => Doctor::class,
                    'invoiceable_id'   => $doctorMail->doctor_id,
                    'description'      => "فاتورة طلب أوراق خارجية رقم {$doctorMail->id}",
                    'amount'           => $doctorMail->grand_total,
                    'status'           => InvoiceStatus::unpaid,
                    'branch_id'      => $doctorMail->doctor->branch_id,
                    'user_id' => auth()->user()->id,
                ]);

                if($doctorMail->doctor->email)
                {
                    Mail::to($doctorMail->doctor->email)->send(new RequestPendingPayment($doctorMail));
                }

            }

            DB::commit();
            return redirect()->route(get_area_name().'.doctor-mails.show',$doctorMail)
                             ->with('success','تم حفظ قرارات الاعتماد بنجاح.');
        }catch(\Throwable $e){
            DB::rollBack();
            \Log::error($e);
            return back()->withErrors($e->getMessage());
        }
    }

    /* =============================================================== */
    public function destroy(DoctorMail $doctorMail)
    {
        DB::transaction(function() use ($doctorMail){
            foreach($doctorMail->doctorMailItems as $item){
                if($item->file) Storage::disk('public')->delete($item->file);
                $item->delete();
            }
            $doctorMail->delete();
        });

        return redirect()->route(get_area_name().'.doctor-mails.index')
                         ->with('success','تم حذف الطلب بنجاح');
    }

    public function markComplete(DoctorMail $doctorMail)
    {
        if ($doctorMail->status !== 'under_proccess') {
            return back()->withErrors('لا يمكن إكمال هذا الطلب في حالته الحالية.');
        }
    
        $doctorMail->update(['status' => 'done']);
    
        // sending complete email to doctor
        if ($doctorMail->doctor->email) {
            Mail::to($doctorMail->doctor->email)->send(new CompletedMail($doctorMail));
        }

        return redirect()
            ->route(get_area_name() . '.doctor-mails.index')
            ->with('success', 'تم إنهاء الطلب بنجاح.');
    }


    public function print(DoctorMail $doctorMail)
    {
        $doctorMail->load(['doctor','doctorMailItems.pricing']);
        $doctorMail->country_names = Country::whereIn('id',$doctorMail->countries)
                                            ->pluck('name')
                                            ->implode(', ');
    
        return view('general.doctor_mails.print', compact('doctorMail'));
    }
    
    
}
