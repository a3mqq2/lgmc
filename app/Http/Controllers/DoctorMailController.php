<?php

namespace App\Http\Controllers;

use App\Models\Email;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Models\DoctorMail;
use App\Mail\CompletedMail;
use App\Models\InvoiceItem;
use App\Enums\InvoiceStatus;
use Illuminate\Http\Request;
use App\Models\DoctorMailItem;
use App\Models\DoctorMailEmail;
use App\Models\DoctorMailCountry;
use App\Models\DoctorMailService;
use Illuminate\Support\Facades\DB;
use App\Mail\RequestPendingPayment;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use App\Mail\RequestUnderModification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\DocumentPreparation;
use App\Models\InternshipTrainingRecord;
use App\Models\InternshipGap;

class DoctorMailController extends Controller
{
    public function index(Request $request)
    {
        // Start query
        $query = DoctorMail::with(['doctor', 'services', 'emails', 'countries', 'services']);
    
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                  ->orWhereHas('doctor', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%")
                        ->orWhere('code', 'like', "%{$search}%")
                        ->orWhere('phone', 'like', "%{$search}%");
                  })
                  ->orWhereHas('emails', function ($q) use ($search) {
                      $q->where('email_value', 'like', "%{$search}%");
                  });
            });
        }
    
        // Doctor filter
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
    
        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
    
        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
    
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        
    
        $query->orderBy($sortBy, $sortOrder);
    
        // Paginate results
        $doctorMails = $query->paginate(15)->withQueryString();
    
        // Get additional data for filters
        $doctors = Doctor::orderBy('name')->get(['id', 'name', 'code']);
        $services = Pricing::where('type', 'service')->orderBy('name')->get(['id', 'name']);
    
        return view('general.doctor_mails.index', compact(
            'doctorMails',
            'doctors',
            'services'
        ));
    }
    
    /* =============================================================== */
    public function create()   { return view('general.doctor_mails.create'); }

    /* ===============================================================
     * تخزين طلب جديد 
     * =============================================================== */

     
     public function store(Request $request)
     {
         // التحقق من صحة البيانات
         $validator = Validator::make($request->all(), [
             'doctor_id' => 'required|exists:doctors,id',
             'emails' => 'required|array|min:1',
             'emails.*.value' => 'required',
             'emails.*.is_new' => 'required|boolean',
             'services' => 'required|array|min:1',
             'services.*.id' => 'required|exists:pricings,id',
             'services.*.amount' => 'required|numeric|min:0',
             'services.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120', // 5MB
             'services.*.file_required' => 'required|boolean',
             'services.*.work_mention' => 'nullable|in:with,without',
             'countries' => 'nullable|array',
             'countries.*.value' => 'required|string',
             'countries.*.is_new' => 'required|boolean',
             'notes' => 'nullable|string|max:1000',
             'extracted_before' => 'required|boolean',
             'last_extract_year' => 'nullable|integer|min:1990|max:' . date('Y'),
         ], [
             'doctor_id.required' => 'يرجى اختيار طبيب',
             'doctor_id.exists' => 'الطبيب المختار غير موجود',
             'emails.required' => 'يرجى إضافة بريد إلكتروني واحد على الأقل',
             'emails.min' => 'يرجى إضافة بريد إلكتروني واحد على الأقل',
             'emails.*.value.required' => 'البريد الإلكتروني مطلوب',
             'emails.*.value.email' => 'البريد الإلكتروني غير صالح',
             'services.required' => 'يرجى اختيار خدمة واحدة على الأقل',
             'services.min' => 'يرجى اختيار خدمة واحدة على الأقل',
             'services.*.id.required' => 'معرف الخدمة مطلوب',
             'services.*.id.exists' => 'الخدمة المختارة غير موجودة',
             'services.*.file.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: PDF, JPG, PNG, DOC, DOCX',
             'services.*.file.max' => 'حجم الملف يجب أن يكون أقل من 5 ميجابايت',
             'notes.max' => 'الملاحظات يجب أن تكون أقل من 1000 حرف',
             'last_extract_year.min' => 'سنة الاستخراج يجب أن تكون من 1990 فما فوق',
             'last_extract_year.max' => 'سنة الاستخراج يجب أن تكون ' . date('Y') . ' أو أقل',
         ]);
     
         if ($validator->fails()) {
             return response()->json([
                 'success' => false,
                 'message' => 'يرجى التحقق من البيانات المدخلة',
                 'errors' => $validator->errors()
             ], 422);
         }
     
         try {
             DB::beginTransaction();
     
             // جلب بيانات الطبيب للتحقق من النوع
             $doctor = Doctor::findOrFail($request->doctor_id);
     
             // التحقق من صحة الخدمات والملفات المطلوبة
             $totalAmount = 0;
             $servicesData = [];
     
             foreach ($request->services as $serviceData) {
                 $pricing = Pricing::find($serviceData['id']);
                 
                 if (!$pricing) {
                     throw new \Exception("الخدمة غير موجودة: {$serviceData['id']}");
                 }
     
                 // التحقق من تطابق نوع الطبيب مع الخدمة
                 if ($pricing->doctor_type !== $doctor->type) {
                     throw new \Exception("الخدمة {$pricing->name} غير متاحة لهذا النوع من الأطباء");
                 }
     
                 // التحقق من الملفات المطلوبة
                 $fileRequired = isset($serviceData['file_required']) && $serviceData['file_required'];
                 if ($fileRequired && !isset($serviceData['file'])) {
                     throw new \Exception("الملف مطلوب للخدمة: {$pricing->name}");
                 }
     
                 // التحقق من خيارات جهة العمل للخدمات المحددة
                 $workMentionRequired = in_array($pricing->id, [8, 23, 36]);
                 if ($workMentionRequired && !isset($serviceData['work_mention'])) {
                     throw new \Exception("يرجى تحديد خيار جهة العمل للخدمة: {$pricing->name}");
                 }
     
                 $totalAmount += $pricing->amount;
                 $servicesData[] = [
                     'pricing' => $pricing,
                     'data' => $serviceData
                 ];
             }
     
             // حساب تكلفة البريد الإلكتروني
             $emailPricing = Pricing::where('type', 'email')
                                   ->where('doctor_type', $doctor->type)
                                   ->first();
             
             $emailUnitPrice = $emailPricing ? $emailPricing->amount : 0;
             $totalEmailAmount = $emailUnitPrice * count($request->emails);
             $grandTotal = $totalAmount + $totalEmailAmount;
     
             // إنشاء الطلب الرئيسي
             $doctorMail = DoctorMail::create([
                 'doctor_id' => $doctor->id,
                 'total_services_amount' => $totalAmount,
                 'total_email_amount' => $totalEmailAmount,
                 'email_unit_price' => $emailUnitPrice,
                 'total_emails_count' => count($request->emails),
                 'grand_total' => $grandTotal,
                 'notes' => $request->notes,
                 'extracted_before' => $request->extracted_before,
                 'last_extract_year' => $request->extracted_before ? $request->last_extract_year : null,
                 'status' => 'under_approve', // pending, in_progress, completed, rejected
                 'created_by' => auth()->id(),
             ]);
     
             // معالجة الإيميلات
             foreach ($request->emails as $emailData) {
                 $emailId = null;
                 
                 if (!$emailData['is_new']) {
                     // البحث عن الإيميل الموجود
                     $existingEmail = Email::where('email', $emailData['value'])->first();
                     if ($existingEmail) {
                         $emailId = $existingEmail->id;
                     }
                 }
     
                 // إذا كان الإيميل جديد أو لم يوجد في القاعدة، أنشئه
                 if (!$emailId) {
                     $newEmail = Email::create(['email' => $emailData['value']]);
                     $emailId = $newEmail->id;
                 }
     
                 // ربط الإيميل بالطلب
                 DoctorMailEmail::create([
                     'doctor_mail_id' => $doctorMail->id,
                     'email_id' => $emailId,
                     'email_value' => $emailData['value'],
                     'is_new' => $emailData['is_new'],
                     'unit_price' => $emailUnitPrice,
                 ]);
             }
     
             // معالجة الدول (إذا وُجدت)
             if (!empty($request->countries)) {
                 foreach ($request->countries as $countryData) {
                     $countryId = null;
                     
                     if (!$countryData['is_new']) {
                         // البحث عن الدولة الموجودة
                         $existingCountry = Country::where('country_name_ar', $countryData['value'])->first();
                         if ($existingCountry) {
                             $countryId = $existingCountry->id;
                         }
                     }
     
                     // إذا كانت الدولة جديدة أو لم توجد، قم بإنشائها (إذا كان مسموحاً)
                     if (!$countryId && $countryData['is_new']) {
                         // يمكنك إنشاء دولة جديدة أو تخزين الاسم فقط
                         // هنا سأخزن الاسم فقط
                     }
     
                     // ربط الدولة بالطلب
                     DoctorMailCountry::create([
                         'doctor_mail_id' => $doctorMail->id,
                         'country_id' => $countryId,
                         'is_new' => $countryData['is_new'],
                         "country_value" => $countryData['value'],
                     ]);
                 }
             }
     
             // معالجة الخدمات والملفات
             foreach ($servicesData as $index => $serviceItem) {
                 $pricing = $serviceItem['pricing'];
                 $serviceData = $serviceItem['data'];
     
                 // Initialize file variables
                 $filePath = null;
                 $fileName = null;
     
                 // معالجة الملف إذا وُجد
                 if (isset($serviceData['file']) && $serviceData['file']) {
                     $file = $serviceData['file'];
                     $originalName = $file->getClientOriginalName();
                     $extension = $file->getClientOriginalExtension();
                     
                     // إنشاء اسم ملف فريد
                     $uniqueFileName = 'doctor_mail_' . $doctorMail->id . '_service_' . $pricing->id . '_' . time() . '.' . $extension;
                     
                     // رفع الملف
                     $filePath = $file->storeAs('doctor_mails/' . $doctorMail->id, $uniqueFileName, 'public');
                     $fileName = $serviceData['file_name'] ?? $originalName;
                 }
     
                 // إنشاء سجل الخدمة مع معلومات الملف
                 $doctorMailService = DoctorMailService::create([
                     'doctor_mail_id' => $doctorMail->id,
                     'pricing_id' => $pricing->id,
                     'service_name' => $pricing->name,
                     'amount' => $pricing->amount,
                     'work_mention' => $serviceData['work_mention'] ?? null,
                     'file_required' => $serviceData['file_required'] ?? false,
                     'file_path' => $filePath,  // Store file path directly
                     'file_name' => $fileName,  // Store file name directly
                 ]);
                
             }
     
             DB::commit();
     
             // إرجاع استجابة نجح
             return response()->json([
                 'success' => true,
                 'message' => 'تم إرسال الطلب بنجاح',
                 'data' => [
                     'id' => $doctorMail->id,
                     'total_amount' => $grandTotal,
                     'services_count' => count($servicesData),
                     'emails_count' => count($request->emails),
                     'countries_count' => count($request->countries ?? []),
                 ]
             ], 201);
     
         } catch (\Exception $e) {
             DB::rollBack();
             
             \Log::error('Error creating doctor mail request: ' . $e->getMessage(), [
                 'user_id' => auth()->id(),
                 'doctor_id' => $request->doctor_id,
                 'trace' => $e->getTraceAsString()
             ]);
     
             return response()->json([
                 'success' => false,
                 'message' => $e->getMessage() ?: 'حدث خطأ أثناء إنشاء الطلب',
             ], 500);
         }
     }
    /* =============================================================== */
    public function show(DoctorMail $doctorMail)
    {
        return view('general.doctor_mails.show', compact('doctorMail'));
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

                $item=$doctorMail->services()->findOrFail($id);
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
                      try {
                         Mail::to($doctorMail->doctor->email)->send(new RequestUnderModification($doctorMail));
                      } catch(\Throwable $e) {
                     
                      }
                }

            }else{
                $doctorMail->update(['status' => 'under_payment']);

                    // أولًا: البنود العادية (التي ليست بريد إلكتروني)
                    foreach ($doctorMail->services()->where('status', 'approved')->get() as $item) {
                        if (!in_array($item->pricing_id, [55, 56, 57])) {
                            $pricing = \App\Models\Pricing::find($item->pricing_id);

                            Invoice::create([
                                'invoice_number'   => 'DM-' . date('Y') . '-' . $doctorMail->id . '-' . $item->id,
                                'doctor_mail_id'   => $doctorMail->id,
                                'invoiceable_type' => \App\Models\Doctor::class,
                                'invoiceable_id'   => $doctorMail->doctor_id,
                                'description'      => "{$pricing->name} (بند رقم {$item->id})",
                                'amount'           => $pricing->amount,
                                'status'           => \App\Enums\InvoiceStatus::unpaid,
                                'branch_id'        => $doctorMail->doctor->branch_id,
                                'user_id'          => auth()->id(),
                            ]);
                        }
                    }

                    // ثانيًا: البنود الخاصة بالإيميلات (كل إيميل = فاتورة مستقلة)
                    $pricingEmail = \App\Models\Pricing::find(55); // أو استخدم شرط حسب نوع الدكتور

                    foreach ($doctorMail->emails as $index => $email) {
                        Invoice::create([
                            'invoice_number'   => 'DM-' . date('Y') . '-' . $doctorMail->id . '-EMAIL-' . ($index + 1),
                            'doctor_mail_id'   => $doctorMail->id,
                            'invoiceable_type' => \App\Models\Doctor::class,
                            'invoiceable_id'   => $doctorMail->doctor_id,
                            'description'      => "بريد إلكتروني رقم " . ($index + 1) . " ({$email})",
                            'amount'           => $pricingEmail->amount,
                            'status'           => \App\Enums\InvoiceStatus::unpaid,
                            'branch_id'        => $doctorMail->doctor->branch_id,
                            'user_id'          => auth()->id(),
                        ]);
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
            foreach($doctorMail->services as $item){
                if($item->file) Storage::disk('public')->delete($item->file);
                $item->delete();
            }
            $doctorMail->delete();
        });

        return redirect()->route(get_area_name().'.doctor-mails.index')
                         ->with('success','تم حذف الطلب بنجاح');
    }

    /**
     * Mark doctor mail as complete
     */
    public function complete(DoctorMail $doctorMail)
    {
        if ($doctorMail->status !== 'under_proccess') {
            return back()->withErrors('لا يمكن إكمال هذا الطلب في حالته الحالية.');
        }
    
        $doctorMail->update(['status' => 'done']);
    
        // sending complete email to doctor
        if ($doctorMail->doctor->email) {
            try {
                Mail::to($doctorMail->doctor->email)->send(new CompletedMail($doctorMail));

            } catch (\Exception $e) {
                // Don't fail the completion process if email fails
            }
        }

        return redirect()
            ->route(get_area_name() . '.doctor-mails.index')
            ->with('success', 'تم إنهاء الطلب بنجاح.');
    }

    public function markComplete(DoctorMail $doctorMail)
    {
        if ($doctorMail->status !== 'under_proccess') {
            return back()->withErrors('لا يمكن إكمال هذا الطلب في حالته الحالية.');
        }
    
        $doctorMail->update(['status' => 'done']);
    
        // sending complete email to doctor
        if ($doctorMail->doctor->email) {
            try {
                Mail::to($doctorMail->doctor->email)->send(new CompletedMail($doctorMail));
            } catch (\Exception $e) {
                // Don't fail the completion process if email fails
            }
        }

        return redirect()
            ->route(get_area_name() . '.doctor-mails.index')
            ->with('success', 'تم إنهاء الطلب بنجاح.');
    }


    public function print(DoctorMail $doctorMail)
    {
        $doctorMail->load(['doctor','services.pricing']);
        $doctorMail->country_names = Country::whereIn('id',$doctorMail->countries)
                                            ->pluck('name')
                                            ->implode(', ');
    
        return view('general.doctor_mails.print', compact('doctorMail'));
    }
    
    

    public function approve(Request $request, DoctorMail $doctorMail)
    {
        // Check if request can be approved
        if ($doctorMail->status !== 'under_approve') {
            return back()->withErrors('لا يمكن الموافقة على هذا الطلب في حالته الحالية.');
        }
    
        try {
            DB::beginTransaction();
    
            // 1. Update doctor mail status
            $doctorMail->update([
                'status' => 'under_payment',
            ]);
    
            // 2. Create invoice for the doctor mail
            $invoice = new Invoice();
            $invoice->invoice_number = rand(0,999999999);
            $invoice->description =  "فاتورة طلب اوراق بالخارج رقم " . $doctorMail->id;
            $invoice->user_id = auth()->id();
            $invoice->amount = 0;
            $invoice->status = "unpaid";
            $invoice->doctor_id = $doctorMail->doctor_id;
            $invoice->category = "doctor_mail";
            $invoice->doctor_mail_id = $doctorMail->id;
            $invoice->save();


            foreach($doctorMail->services as $service)
            {
                $pricing_service = $service->pricing;
                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_id = $invoice->id;
                $invoice_item->description = $pricing_service->name;
                $invoice_item->amount = $pricing_service->amount;
                $invoice_item->pricing_id = $pricing_service->id;
                $invoice_item->save();
            }


               
            $invoice->update([
                'amount' => $invoice->items()->sum('amount'),
            ]);

    
            DB::commit();
    
            // 3. Send email notification to doctor
            if ($doctorMail->doctor->email) {
                try {
                    Mail::to($doctorMail->doctor->email)->send(new RequestPendingPayment($doctorMail, $invoice));
                } catch (\Exception $e) {
                    \Log::error('Failed to send approval email: ' . $e->getMessage());
                    // Don't fail the approval process if email fails
                }
            }
    
            return redirect()
                ->route(get_area_name() . '.doctor-mails.show', $doctorMail)
                ->with('success', 'تم الموافقة على الطلب بنجاح وتم إنشاء الفاتورة.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error approving doctor mail: ' . $e->getMessage());
            
            return back()->withErrors('حدث خطأ أثناء الموافقة على الطلب: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject doctor mail request and send for edit
     */
    public function reject(Request $request, DoctorMail $doctorMail)
    {
        // Check if request can be rejected
        if ($doctorMail->status !== 'under_approve') {
            return back()->withErrors('لا يمكن إرجاع هذا الطلب للتعديل في حالته الحالية.');
        }
    
        // Validate the edit note
        $request->validate([
            'edit_note' => 'required|string|min:10|max:1000',
        ], [
            'edit_note.required' => 'ملاحظات التعديل مطلوبة',
            'edit_note.min' => 'ملاحظات التعديل يجب أن تكون على الأقل 10 أحرف',
            'edit_note.max' => 'ملاحظات التعديل يجب أن تكون أقل من 1000 حرف'
        ]);
    
        try {
            DB::beginTransaction();
    
            $doctorMail->update([
                'edit_note' => $request->edit_note,
                'status' => 'under_edit',
            ]);
    
    
            DB::commit();
    
            try {
                Mail::to($doctorMail->doctor->email)->send(new RequestUnderModification($doctorMail));
            } catch (\Exception $e) {
                \Log::error('Failed to send edit notification email: ' . $e->getMessage());
            }
            return redirect()
                ->route(get_area_name() . '.doctor-mails.show', $doctorMail)
                ->with('success', 'تم إرجاع الطلب للتعديل بنجاح.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Error rejecting doctor mail: ' . $e->getMessage());
            
            return back()->withErrors('حدث خطأ أثناء إرجاع الطلب للتعديل: ' . $e->getMessage());
        }
    }

    // Add these methods to your DoctorMailController
    
    /**
     * Show the form for editing the doctor mail request
     */
    public function edit(DoctorMail $doctorMail)
    {
        // Check if request can be edited
        if (!in_array($doctorMail->status, ['under_edit', 'under_approve'])) {
            return redirect()->back()->withErrors('لا يمكن تعديل هذا الطلب في حالته الحالية.');
        }
    
        // Check if the current user owns this request (if accessing from doctor area)
        if (get_area_name() === 'doctor' && $doctorMail->doctor_id !== auth()->user('doctor')->id) {
            abort(403, 'غير مسموح لك بالوصول لهذا الطلب.');
        }
    
        $doctorMail->load([
            'doctor',
            'services',
            'emails',
            'countries'
        ]);
    
        return view('doctor.doctor-mails.edit', compact('doctorMail'));
    }
    
    /**
     * Update the doctor mail request
     */
    public function updateRequest(Request $request, DoctorMail $doctorMail)
    {

        
        // Check if request can be updated
        if (!in_array($doctorMail->status, ['under_edit', 'under_approve'])) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن تعديل هذا الطلب في حالته الحالية.'
            ], 403);
        }
    
        // Check ownership for doctor area


// Check ownership for doctor area
        if (get_area_name() === 'doctor' && $doctorMail->doctor_id !== auth()->user('doctor')->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح لك بتعديل هذا الطلب.'
            ], 403);
        }
    
        // Validation
        $validator = Validator::make($request->all(), [
            'emails' => 'required|array|min:1',
            'emails.*.value' => 'required',
            'emails.*.is_new' => 'required|boolean',
            'services' => 'required|array|min:1',
            'services.*.id' => 'required|exists:pricings,id',
            'services.*.amount' => 'required|numeric|min:0',
            'services.*.file' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx|max:5120',
            'services.*.file_required' => 'required|boolean',
            'services.*.work_mention' => 'nullable|in:with,without',
            'countries' => 'nullable|array',
            'countries.*.value' => 'required|string',
            'countries.*.is_new' => 'required|boolean',
            'notes' => 'nullable|string|max:1000',
            'extracted_before' => 'required|boolean',
            'last_extract_year' => 'nullable|integer|min:1990|max:' . date('Y'),
        ], [
            'emails.required' => 'يرجى إضافة بريد إلكتروني واحد على الأقل',
            'emails.min' => 'يرجى إضافة بريد إلكتروني واحد على الأقل',
            'emails.*.value.required' => 'البريد الإلكتروني مطلوب',
            'emails.*.value.email' => 'البريد الإلكتروني غير صالح',
            'services.required' => 'يرجى اختيار خدمة واحدة على الأقل',
            'services.min' => 'يرجى اختيار خدمة واحدة على الأقل',
            'services.*.file.mimes' => 'نوع الملف غير مدعوم. الأنواع المدعومة: PDF, JPG, PNG, DOC, DOCX',
            'services.*.file.max' => 'حجم الملف يجب أن يكون أقل من 5 ميجابايت',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'يرجى التحقق من البيانات المدخلة',
                'errors' => $validator->errors()
            ], 422);
        }
    
        try {
            DB::beginTransaction();
    
            // Get doctor for pricing calculations
            $doctor = $doctorMail->doctor;
    
            // Calculate totals
            $totalServicesAmount = 0;
            $servicesData = [];
    
            foreach ($request->services as $serviceData) {
                $pricing = Pricing::find($serviceData['id']);
                
                if (!$pricing || $pricing->doctor_type !== $doctor->type) {
                    throw new \Exception("الخدمة غير متاحة لهذا النوع من الأطباء");
                }
    
                // Check required fields
                $fileRequired = isset($serviceData['file_required']) && $serviceData['file_required'];
                $hasExistingFile = $serviceData['existing_file_path'] ?? false;
                $hasNewFile = isset($serviceData['file']);
    
                if ($fileRequired && !$hasExistingFile && !$hasNewFile) {
                    throw new \Exception("الملف مطلوب للخدمة: {$pricing->name}");
                }
    
                // Check work mention for specific services
                $workMentionRequired = in_array($pricing->id, [45, 46, 47]);
                if ($workMentionRequired && !isset($serviceData['work_mention'])) {
                    throw new \Exception("يرجى تحديد خيار جهة العمل للخدمة: {$pricing->name}");
                }
    
                $totalServicesAmount += $pricing->amount;
                $servicesData[] = [
                    'pricing' => $pricing,
                    'data' => $serviceData
                ];
            }
    
            // Calculate email costs
            $emailPricing = Pricing::where('type', 'email')
                                  ->where('doctor_type', $doctor->type)
                                  ->first();
            
            $emailUnitPrice = $emailPricing ? $emailPricing->amount : 0;
            $totalEmailAmount = $emailUnitPrice * count($request->emails);
            $grandTotal = $totalServicesAmount + $totalEmailAmount;
    
            // Update doctor mail
            $doctorMail->update([
                'total_services_amount' => $totalServicesAmount,
                'total_email_amount' => $totalEmailAmount,
                'email_unit_price' => $emailUnitPrice,
                'total_emails_count' => count($request->emails),
                'grand_total' => $grandTotal,
                'notes' => $request->notes,
                'extracted_before' => $request->extracted_before,
                'last_extract_year' => $request->extracted_before ? $request->last_extract_year : null,
                'status' => 'under_approve', // Reset to pending approval
                'edit_note' => null, // Clear edit note after update
            ]);
    
            // Delete existing related records
            $doctorMail->emails()->delete();
            $doctorMail->countries()->delete();
            
            // Delete existing services and their files
            foreach ($doctorMail->services as $service) {
                if ($service->file_path) {
                    Storage::disk('public')->delete($service->file_path);
                }
            }
            $doctorMail->services()->delete();
    
            // Re-create emails
            foreach ($request->emails as $emailData) {
                $emailId = null;
                
                if (!$emailData['is_new']) {
                    $existingEmail = Email::where('email', $emailData['value'])->first();
                    if ($existingEmail) {
                        $emailId = $existingEmail->id;
                    }
                }
    
    
                DoctorMailEmail::create([
                    'doctor_mail_id' => $doctorMail->id,
                    'email_id' => isset($emailId) ? $emailId : null,
                    'email_value' => $emailData['value'],
                    'is_new' => $emailData['is_new'],
                    'unit_price' => $emailUnitPrice,
                ]);
            }
    
            // Re-create countries if provided
            if (!empty($request->countries)) {
                foreach ($request->countries as $countryData) {
                    $countryId = null;
                    
                    if (!$countryData['is_new']) {
                        $existingCountry = Country::where('country_name_ar', $countryData['value'])->first();
                        if ($existingCountry) {
                            $countryId = $existingCountry->id;
                        }
                    }
    
                    DoctorMailCountry::create([
                        'doctor_mail_id' => $doctorMail->id,
                        'country_id' => $countryId,
                        'is_new' => $countryData['is_new'],
                        'country_value' => $countryData['value'],
                    ]);
                }
            }
    
            // Re-create services
            foreach ($servicesData as $index => $serviceItem) {
                $pricing = $serviceItem['pricing'];
                $serviceData = $serviceItem['data'];
    
                $filePath = $serviceData['existing_file_path'] ?? null;
                $fileName = $serviceData['existing_file_name'] ?? null;
    
                // Handle new file upload
                if (isset($serviceData['file']) && $serviceData['file']) {
                    // Delete old file if exists
                    if ($filePath) {
                        Storage::disk('public')->delete($filePath);
                    }
    
                    $file = $serviceData['file'];
                    $originalName = $file->getClientOriginalName();
                    $extension = $file->getClientOriginalExtension();
                    
                    $uniqueFileName = 'doctor_mail_' . $doctorMail->id . '_service_' . $pricing->id . '_' . time() . '.' . $extension;
                    $filePath = $file->storeAs('doctor_mails/' . $doctorMail->id, $uniqueFileName, 'public');
                    $fileName = $originalName;
                }
    
                DoctorMailService::create([
                    'doctor_mail_id' => $doctorMail->id,
                    'pricing_id' => $pricing->id,
                    'service_name' => $pricing->name,
                    'amount' => $pricing->amount,
                    'work_mention' => $serviceData['work_mention'] ?? null,
                    'file_required' => $serviceData['file_required'] ?? false,
                    'file_path' => $filePath,
                    'file_name' => $fileName,
                ]);
            }
    
            DB::commit();
    
            return response()->json([
                'success' => true,
                'message' => 'تم تحديث الطلب بنجاح',
                'data' => [
                    'id' => $doctorMail->id,
                    'total_amount' => $grandTotal,
                    'status' => $doctorMail->status
                ]
            ]);
    
        } catch (\Exception $e) {
            DB::rollBack();
            
            \Log::error('Error updating doctor mail request: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'doctor_mail_id' => $doctorMail->id,
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'حدث خطأ أثناء تحديث الطلب',
            ], 500);
        }
    }
    
    /**
     * Get doctor mail data for editing (API endpoint)
     */
    public function getDoctorMailData(DoctorMail $doctorMail)
    {
        // Check ownership for doctor area
        if (get_area_name() === 'doctor' && $doctorMail->doctor_id !== auth()->user('doctor')->id) {
            return response()->json([
                'success' => false,
                'message' => 'غير مسموح لك بالوصول لهذا الطلب.'
            ], 403);
        }
    
        $doctorMail->load([
            'doctor',
            'services',
            'emails',
            'countries'
        ]);
    
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $doctorMail->id,
                'doctor' => [
                    'id' => $doctorMail->doctor->id,
                    'name' => $doctorMail->doctor->name,
                    'code' => $doctorMail->doctor->code,
                    'type' => $doctorMail->doctor->type,
                    'label' => $doctorMail->doctor->name . ' (' . $doctorMail->doctor->code . ')'
                ],
                'services' => $doctorMail->services->map(function ($service) {
                    return [
                        'id' => $service->pricing_id,
                        'pricing_id' => $service->pricing_id,
                        'service_name' => $service->service_name,
                        'amount' => $service->amount,
                        'work_mention' => $service->work_mention,
                        'file_required' => $service->file_required,
                        'file_path' => $service->file_path,
                        'file_name' => $service->file_name,
                        'label' => $service->service_name . ' (' . number_format($service->amount, 2) . ' د.ل)'
                    ];
                }),
                'emails' => $doctorMail->emails->map(function ($email) {
                    return [
                        'id' => $email->email_id,
                        'value' => $email->email_value,
                        'label' => $email->email_value,
                        'isNew' => $email->is_new,
                        'unit_price' => $email->unit_price
                    ];
                }),
                'countries' => $doctorMail->countries->map(function ($country) {
                    return [
                        'id' => $country->country_id ?: 'new_' . $country->id,
                        'value' => $country->country_value,
                        'label' => $country->country_value,
                        'isNew' => $country->is_new
                    ];
                }),
                'notes' => $doctorMail->notes,
                'extracted_before' => $doctorMail->extracted_before,
                'last_extract_year' => $doctorMail->last_extract_year,
                'edit_note' => $doctorMail->edit_note,
                'status' => $doctorMail->status,
                'totals' => [
                    'services_amount' => $doctorMail->total_services_amount,
                    'email_amount' => $doctorMail->total_email_amount,
                    'grand_total' => $doctorMail->grand_total,
                    'email_unit_price' => $doctorMail->email_unit_price,
                    'emails_count' => $doctorMail->total_emails_count
                ]
            ]
        ]);
    }    

    /**
     * إعداد المستند للخدمة المحددة
     *
     * @param Request $request
     * @param DoctorMail $doctorMail
     * @param int $serviceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function prepareDocument(Request $request, DoctorMail $doctorMail, int $serviceId)
    {
        try {
            // العثور على الخدمة المحددة في الطلب
            $doctorMailService = $doctorMail->services()->where('id', $serviceId)->first();
            
            if (!$doctorMailService) {
                return response()->json([
                    'success' => false,
                    'message' => 'الخدمة غير موجودة'
                ], 404);
            }

            // الحصول على نوع المستند من pricing
            $documentType = $doctorMailService->pricing->document_type ?? null;
            
            if (!$documentType) {
                return response()->json([
                    'success' => false,
                    'message' => 'نوع المستند غير محدد في تسعير الخدمة'
                ], 400);
            }

            // التحقق من وجود إعداد مسبق للمستند
            $existingPreparation = DocumentPreparation::where([
                'doctor_mail_id' => $doctorMail->id,
                'service_id' => $serviceId
            ])->first();

            if ($existingPreparation && $existingPreparation->is_prepared) {
                return response()->json([
                    'success' => false,
                    'message' => 'تم إعداد هذا المستند مسبقاً في ' . $existingPreparation->prepared_at->format('Y-m-d H:i')
                ], 422);
            }

            // التحقق من صحة البيانات حسب نوع المستند
            $validatedData = $this->validateDocumentData($request, $documentType);
            
            if (!$validatedData['valid']) {
                return response()->json([
                    'success' => false,
                    'message' => $validatedData['message'],
                    'errors' => $validatedData['errors'] ?? []
                ], 422);
            }

            DB::beginTransaction();

            try {
                // إنشاء أو تحديث سجل إعداد المستند
                $documentPreparation = DocumentPreparation::updateOrCreate(
                    [
                        'doctor_mail_id' => $doctorMail->id,
                        'service_id' => $serviceId
                    ],
                    [
                        'document_type' => $documentType,
                        'preparation_data' => $validatedData['data'],
                        'status' => 'pending'
                    ]
                );

                // معالجة البيانات حسب نوع المستند
                $result = $this->processDocumentByType($documentType, $validatedData['data'], $documentPreparation);

                if ($result['success']) {
                    // تحديث حالة إعداد المستند
                    $documentPreparation->markAsPrepared($result['document_path'] ?? null, $result['notes'] ?? null);

                    DB::commit();

                    // تسجيل العملية في السجل
                    Log::info('تم إعداد مستند بنجاح', [
                        'document_type' => $documentType,
                        'doctor_mail_id' => $doctorMail->id,
                        'service_id' => $serviceId,
                        'user_id' => auth()->id()
                    ]);

                    return response()->json([
                        'success' => true,
                        'message' => $result['message'],
                        'document_path' => $result['document_path'] ?? null,
                        'preparation_id' => $documentPreparation->id,
                        'preparation_data' => $documentPreparation->fresh(),
                        'redirect_url' => route('admin.document-preparations.print', $documentPreparation->id)
                    ]);
                }

                DB::rollback();
                return response()->json([
                    'success' => false,
                    'message' => $result['message']
                ], 500);

            } catch (\Exception $e) {
                DB::rollback();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('خطأ في إعداد المستند', [
                'error' => $e->getMessage(),
                'doctor_mail_id' => $doctorMail->id,
                'service_id' => $serviceId,
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد المستند. يرجى المحاولة مرة أخرى.'
            ], 500);
        }
    }

    /**
     * التحقق من صحة البيانات حسب نوع المستند
     *
     * @param Request $request
     * @param string $documentType
     * @return array
     */
    private function validateDocumentData(Request $request, string $documentType): array
    {
        switch ($documentType) {
            case 'license':
            case 'good_standing':
            case 'certificate':
            case 'verification_work':
            case 'university_letters':
                // لا توجد بيانات إضافية مطلوبة لهذه الأنواع
                return [
                    'valid' => true,
                    'data' => [
                        'document_type' => $documentType,
                        'prepared_at' => now()->toDateTimeString()
                    ]
                ];

            case 'specialist':
                $validator = Validator::make($request->all(), [
                    'doctor_type' => 'required|in:specialist,consultant'
                ], [
                    'doctor_type.required' => 'يرجى اختيار نوع الطبيب',
                    'doctor_type.in' => 'نوع الطبيب يجب أن يكون اختصاصي أو استشاري'
                ]);

                if ($validator->fails()) {
                    return [
                        'valid' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ];
                }

                return [
                    'valid' => true,
                    'data' => [
                        'document_type' => $documentType,
                        'doctor_type' => $request->doctor_type,
                        'doctor_type_label' => $request->doctor_type === 'specialist' ? 'اختصاصي' : 'استشاري'
                    ]
                ];

            case 'internship_second_year':
                $validator = Validator::make($request->all(), [
                    'specialty' => 'required|array|min:1',
                    'specialty.*' => 'required|string|min:2|max:100',
                    'institution' => 'required|array|min:1',
                    'institution.*' => 'required|string|min:2|max:200',
                    'from_date' => 'required|array|min:1',
                    'from_date.*' => 'required|date|before_or_equal:today',
                    'to_date' => 'required|array|min:1',
                    'to_date.*' => 'required|date|after_or_equal:from_date.*|before_or_equal:today',
                    'has_gap' => 'sometimes|boolean',
                    'gap_from_date' => 'required_if:has_gap,true|date|before_or_equal:today',
                    'gap_to_date' => 'required_if:has_gap,true|date|after_or_equal:gap_from_date|before_or_equal:today',
                    'gap_reason' => 'required_if:has_gap,true|string|min:10|max:1000'
                ], [
                    'specialty.required' => 'يرجى إدخال التخصص',
                    'specialty.min' => 'يجب إدخال تخصص واحد على الأقل',
                    'institution.required' => 'يرجى إدخال المؤسسة',
                    'from_date.required' => 'يرجى إدخال تاريخ البداية',
                    'to_date.required' => 'يرجى إدخال تاريخ النهاية',
                    'to_date.*.after_or_equal' => 'تاريخ النهاية يجب أن يكون بعد أو يساوي تاريخ البداية',
                    'gap_reason.min' => 'سبب الفجوة يجب أن يكون أكثر تفصيلاً (10 أحرف على الأقل)',
                    'gap_reason.max' => 'سبب الفجوة طويل جداً'
                ]);

                if ($validator->fails()) {
                    return [
                        'valid' => false,
                        'message' => $validator->errors()->first(),
                        'errors' => $validator->errors()
                    ];
                }

                // التحقق من عدم تداخل التواريخ
                $dateValidation = $this->validateTrainingDates($request->from_date, $request->to_date);
                if (!$dateValidation['valid']) {
                    return $dateValidation;
                }

                // تجميع بيانات سجلات التدريب
                $trainingRecords = [];
                for ($i = 0; $i < count($request->specialty); $i++) {
                    $trainingRecords[] = [
                        'specialty' => $request->specialty[$i],
                        'institution' => $request->institution[$i],
                        'from_date' => $request->from_date[$i],
                        'to_date' => $request->to_date[$i]
                    ];
                }

                $data = [
                    'document_type' => $documentType,
                    'training_records' => $trainingRecords,
                    'total_records' => count($trainingRecords)
                ];

                if ($request->boolean('has_gap')) {
                    $data['gap'] = [
                        'from_date' => $request->gap_from_date,
                        'to_date' => $request->gap_to_date,
                        'reason' => $request->gap_reason
                    ];
                }

                return [
                    'valid' => true,
                    'data' => $data
                ];

            default:
                return [
                    'valid' => false,
                    'message' => 'نوع المستند غير مدعوم: ' . $documentType
                ];
        }
    }

    /**
     * التحقق من صحة تواريخ التدريب وعدم تداخلها
     *
     * @param array $fromDates
     * @param array $toDates
     * @return array
     */
    private function validateTrainingDates(array $fromDates, array $toDates): array
    {
        $dates = [];
        
        // تحويل التواريخ وتجميعها
        for ($i = 0; $i < count($fromDates); $i++) {
            $dates[] = [
                'from' => \Carbon\Carbon::parse($fromDates[$i]),
                'to' => \Carbon\Carbon::parse($toDates[$i]),
                'index' => $i + 1
            ];
        }

        // التحقق من التداخل
        for ($i = 0; $i < count($dates); $i++) {
            for ($j = $i + 1; $j < count($dates); $j++) {
                if ($this->datesOverlap($dates[$i], $dates[$j])) {
                    return [
                        'valid' => false,
                        'message' => "هناك تداخل في تواريخ التدريب بين السجل {$dates[$i]['index']} والسجل {$dates[$j]['index']}"
                    ];
                }
            }
        }

        return ['valid' => true];
    }

    /**
     * التحقق من تداخل فترتين زمنيتين
     *
     * @param array $period1
     * @param array $period2
     * @return bool
     */
    private function datesOverlap(array $period1, array $period2): bool
    {
        return $period1['from'] <= $period2['to'] && $period1['to'] >= $period2['from'];
    }

    /**
     * معالجة إعداد المستند حسب النوع
     *
     * @param string $documentType
     * @param array $data
     * @param DocumentPreparation $documentPreparation
     * @return array
     */
    private function processDocumentByType(string $documentType, array $data, DocumentPreparation $documentPreparation): array
    {
        switch ($documentType) {
            case 'license':
                return $this->prepareLicenseDocument($documentPreparation);

            case 'certificate':
                return $this->prepareCertificateDocument($documentPreparation, $data);

            case 'good_standing':
                return $this->prepareGoodStandingDocument($documentPreparation);

            case 'verification_work':
                return $this->prepareVerificationWorkDocument($documentPreparation);

            case 'university_letters':
                return $this->prepareUniversityLettersDocument($documentPreparation);

            case 'specialist':
                return $this->prepareSpecialistDocument($documentPreparation, $data);

            case 'internship_second_year':
                return $this->prepareInternshipDocument($documentPreparation, $data);

            default:
                return [
                    'success' => false,
                    'message' => 'نوع المستند غير مدعوم'
                ];
        }
    }

    /**
     * إعداد رخصة الممارسة
     *
     * @param DocumentPreparation $documentPreparation
     * @return array
     */
    private function prepareLicenseDocument(DocumentPreparation $documentPreparation): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            return [
                'success' => true,
                'message' => 'تم إعداد رخصة الممارسة بنجاح',
                'document_path' => null,
                'notes' => "تم إعداد رخصة الممارسة للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد رخصة الممارسة: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد رخصة الممارسة'
            ];
        }
    }

    /**
     * إعداد التعريف للدولة المحددة
     *
     * @param DocumentPreparation $documentPreparation
     * @param array $data
     * @return array
     */
    private function prepareCertificateDocument(DocumentPreparation $documentPreparation, array $data = []): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            return [
                'success' => true,
                'message' => 'تم إعداد التعريف بنجاح',
                'document_path' => null,
                'notes' => "تم إعداد التعريف للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد التعريف: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد التعريف'
            ];
        }
    }

    /**
     * إعداد شهادة حسن السيرة والسلوك
     *
     * @param DocumentPreparation $documentPreparation
     * @return array
     */
    private function prepareGoodStandingDocument(DocumentPreparation $documentPreparation): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            return [
                'success' => true,
                'message' => 'تم إعداد شهادة حسن السيرة والسلوك بنجاح',
                'document_path' => null,
                'notes' => "تم إعداد شهادة حسن السيرة والسلوك للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد شهادة حسن السيرة والسلوك: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد شهادة حسن السيرة والسلوك'
            ];
        }
    }

    /**
     * إعداد رسالة التدريب
     *
     * @param DocumentPreparation $documentPreparation
     * @return array
     */
    private function prepareVerificationWorkDocument(DocumentPreparation $documentPreparation): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            return [
                'success' => true,
                'message' => 'تم إعداد رسالة التدريب بنجاح',
                'document_path' => null,
                'notes' => "تم إعداد رسالة التدريب للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد رسالة التدريب: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد رسالة التدريب'
            ];
        }
    }

    /**
     * إعداد رسائل الجامعة
     *
     * @param DocumentPreparation $documentPreparation
     * @return array
     */
    private function prepareUniversityLettersDocument(DocumentPreparation $documentPreparation): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            return [
                'success' => true,
                'message' => 'تم إعداد رسائل الجامعة بنجاح',
                'document_path' => null,
                'notes' => "تم إعداد رسائل الجامعة للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد رسائل الجامعة: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد رسائل الجامعة'
            ];
        }
    }

    /**
     * إعداد رسالة تحقق من اختصاصي أو استشاري
     *
     * @param DocumentPreparation $documentPreparation
     * @param array $data
     * @return array
     */
    private function prepareSpecialistDocument(DocumentPreparation $documentPreparation, array $data): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            $typeLabel = $data['doctor_type_label'];
            
            return [
                'success' => true,
                'message' => "تم إعداد رسالة تحقق من {$typeLabel} بنجاح",
                'document_path' => null,
                'notes' => "تم إعداد رسالة تحقق من {$typeLabel} للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];
        } catch (\Exception $e) {
            Log::error('خطأ في إعداد رسالة تحقق الاختصاص: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء إعداد رسالة تحقق الاختصاص'
            ];
        }
    }

    /**
     * إعداد شهادة السنة الثانية للامتياز
     *
     * @param DocumentPreparation $documentPreparation
     * @param array $data
     * @return array
     */
    private function prepareInternshipDocument(DocumentPreparation $documentPreparation, array $data): array
    {
        try {
            $doctor = $documentPreparation->doctorMail->doctor;
            
            // إنشاء سجلات التدريب
            foreach ($data['training_records'] as $recordData) {
                InternshipTrainingRecord::create([
                    'document_preparation_id' => $documentPreparation->id,
                    'specialty' => $recordData['specialty'],
                    'institution' => $recordData['institution'],
                    'from_date' => $recordData['from_date'],
                    'to_date' => $recordData['to_date']
                ]);
            }

            // إنشاء سجل الفجوة إن وجد
            if (isset($data['gap'])) {
                InternshipGap::create([
                    'document_preparation_id' => $documentPreparation->id,
                    'from_date' => $data['gap']['from_date'],
                    'to_date' => $data['gap']['to_date'],
                    'reason' => $data['gap']['reason']
                ]);
            }

            $recordsCount = $data['total_records'];
            $message = "تم إعداد شهادة السنة الثانية للامتياز مع {$recordsCount} سجل تدريب";
            
            if (isset($data['gap'])) {
                $message .= ' مع وجود فجوة في التدريب';
            }
            
            $message .= ' بنجاح';
            
            return [
                'success' => true,
                'message' => $message,
                'document_path' => null,
                'notes' => $message . " للطبيب {$doctor->name} (كود: {$doctor->code})"
            ];

        } catch (\Exception $e) {
            Log::error('خطأ في إنشاء سجلات التدريب: ' . $e->getMessage());
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء حفظ بيانات التدريب'
            ];
        }
    }

    /**
     * عرض تفاصيل إعداد المستند
     *
     * @param DoctorMail $doctorMail
     * @param DocumentPreparation $documentPreparation
     * @return \Illuminate\View\View
     */
    public function showDocumentPreparation(DoctorMail $doctorMail, DocumentPreparation $documentPreparation)
    {
        // التأكد من أن المستند يخص الطلب المحدد
        if ($documentPreparation->doctor_mail_id !== $doctorMail->id) {
            abort(404);
        }

        $documentPreparation->load(['service', 'preparedBy', 'trainingRecords', 'gaps']);

        return view('admin.doctor-mails.document-preparation', compact('doctorMail', 'documentPreparation'));
    }

    /**
     * تحديث حالة إعداد المستند إلى مكتمل
     *
     * @param DoctorMail $doctorMail
     * @param DocumentPreparation $documentPreparation
     * @return \Illuminate\Http\JsonResponse
     */
    public function completeDocumentPreparation(DoctorMail $doctorMail, DocumentPreparation $documentPreparation)
    {
        try {
            if ($documentPreparation->doctor_mail_id !== $doctorMail->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'المستند غير متطابق مع الطلب'
                ], 400);
            }

            if (!$documentPreparation->is_prepared) {
                return response()->json([
                    'success' => false,
                    'message' => 'لا يمكن إكمال مستند غير معد'
                ], 400);
            }

            $documentPreparation->markAsCompleted();

            return response()->json([
                'success' => true,
                'message' => 'تم تحديث حالة المستند إلى مكتمل بنجاح'
            ]);

        } catch (\Exception $e) {
            Log::error('خطأ في إكمال المستند: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء إكمال المستند'
            ], 500);
        }
    }

    /**
     * طباعة صفحة إعداد المستند
     *
     * @param DocumentPreparation $documentPreparation
     * @return \Illuminate\View\View
     */
    public function printDocumentPreparation(DocumentPreparation $documentPreparation)
    {
        $documentPreparation->load([
            'doctorMail.doctor', 
            'service', 
            'preparedBy', 
            'trainingRecords', 
            'gaps'
        ]);

        $documentTypesNames = [
            'license' => 'رخصة الممارسة',
            'certificate' => 'تعريف',
            'good_standing' => 'شهادة حسن السيرة والسلوك',
            'verification_work' => 'رسالة تدريب',
            'university_letters' => 'رسائل جامعة',
            'specialist' => 'رسالة تحقق من اختصاصي/استشاري',
            'internship_second_year' => 'شهادة السنة الثانية للامتياز'
        ];

        $documentTypeName = $documentTypesNames[$documentPreparation->document_type] ?? $documentPreparation->document_type;

        return view('admin.document-preparations.print', compact(
            'documentPreparation', 
            'documentTypeName'
        ));
    }

    /**
     * تصدير المستند إلى PDF
     *
     * @param DocumentPreparation $documentPreparation
     * @return \Illuminate\Http\Response
     */
    public function exportToPdf(DocumentPreparation $documentPreparation)
    {
        $documentPreparation->load([
            'doctorMail.doctor', 
            'service', 
            'preparedBy', 
            'trainingRecords', 
            'gaps'
        ]);

        $documentTypesNames = [
            'license' => 'رخصة الممارسة',
            'certificate' => 'تعريف',
            'good_standing' => 'شهادة حسن السيرة والسلوك',
            'verification_work' => 'رسالة تدريب',
            'university_letters' => 'رسائل جامعة',
            'specialist' => 'رسالة تحقق من اختصاصي/استشاري',
            'internship_second_year' => 'شهادة السنة الثانية للامتياز'
        ];

        $documentTypeName = $documentTypesNames[$documentPreparation->document_type] ?? $documentPreparation->document_type;

        // استخدام مكتبة PDF مثل Dompdf أو wkhtmltopdf
        $pdf = \PDF::loadView('admin.document-preparations.pdf', compact(
            'documentPreparation', 
            'documentTypeName'
        ));

        $fileName = "document_{$documentPreparation->id}_{$documentPreparation->document_type}.pdf";

        return $pdf->download($fileName);
    }
}