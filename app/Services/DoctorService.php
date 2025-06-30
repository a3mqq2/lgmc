<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Log;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Pricing;
use App\Models\FileType;
use App\Enums\DoctorType;
use App\Models\Blacklist;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\University;
use App\Mail\FinalApproval;
use App\Mail\FirstApproval;
use App\Models\Institution;
use App\Mail\RejectionEmail;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log as FacadesLog;

class DoctorService
{
    /**
     * Create a new doctor.
     *
     * @param  array  $data
     * @return Doctor
     */

     protected $invoiceService;

     public function __construct(InvoiceService $invoiceService)
     {
            $this->invoiceService = $invoiceService;
     }

     public function getDoctors($without_paginate = false)
     {
         $query = Doctor::query();
     
         /* -------------------------------------------------
          |  الفلاتر العامة على الأطباء
          * ------------------------------------------------*/
         if (request()->filled('branch_id'))      $query->where('branch_id', request('branch_id'));
         if (request()->filled('doctor_number'))  $query->where('doctor_number', request('doctor_number'));
         if (request()->filled('name'))           $query->where('name', 'like', '%' . request('name') . '%');
         if (request()->filled('phone'))          $query->where('phone', 'like', '%' . request('phone') . '%');
         if (request()->filled('email'))          $query->where('email', 'like', '%' . request('email') . '%');
         if (request()->filled('registered_at'))  $query->whereDate('registered_at', request('registered_at'));
         if (request()->filled('membership_status')) $query->where('membership_status', request('membership_status'));
         if (request()->filled('specialization')) $query->where('specialty_1_id', request('specialization'));
         if (request()->filled('national_number')) $query->where('national_number', 'like', '%' . request('national_number') . '%');
         if (request()->filled('doctor_rank_id')) $query->where('doctor_rank_id', request('doctor_rank_id'));
         if (request()->filled('passport_number')) $query->where('passport_number', 'like', '%' . request('passport_number') . '%');
         if (request()->filled('academic_degree')) $query->where('academic_degree_id', request('academic_degree'));
         if (request('type'))                     $query->where('type', request('type'));
         if (request()->filled('banned'))         $query->where('membership_status', 'banned');
     
         /* -------------------------------------------------
          |  فلترة حالة الرخصة
          * ------------------------------------------------*/
         if (request()->filled('last_license_status')) {
             $query->whereHas('licenses', function ($q) {
                 $q->where('status', request('last_license_status'));
             });
         }
     
         /* -------------------------------------------------
          |  الفلاتر المالية (منطقة المالية فقط)
          * ------------------------------------------------*/
         if (get_area_name() === 'finance') {
     
             // الأطباء الذين لديهم مستحقات غير مدفوعة / أو ليس لديهم مستحقات
             if (request()->filled('payment_status')) {
                 switch (request('payment_status')) {
                     case 'has_unpaid':
                         $query->whereIn('id', function ($q) {
                             $q->select('doctor_id')
                               ->from('invoices')
                               ->where('status', \App\Enums\InvoiceStatus::unpaid)
                               ->groupBy('doctor_id')
                               ->havingRaw('SUM(amount) > 0');
                         });
                         break;
     
                     case 'no_dues':
                         $query->whereNotIn('id', function ($q) {
                             $q->select('doctor_id')
                               ->from('invoices')
                               ->where('status', \App\Enums\InvoiceStatus::unpaid)
                               ->groupBy('doctor_id');
                         });
                         break;
                 }
             }
     
             // الحدّ الأدنى للمستحقات
             if (request()->filled('min_amount')) {
                 $min = (float) request('min_amount');
                 $query->whereIn('id', function ($q) use ($min) {
                     $q->select('doctor_id')
                       ->from('invoices')
                       ->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->groupBy('doctor_id')
                       ->havingRaw('SUM(amount) >= ?', [$min]);
                 });
             }
     
             // الحدّ الأقصى للمستحقات
             if (request()->filled('max_amount')) {
                 $max = (float) request('max_amount');
                 $query->whereIn('id', function ($q) use ($max) {
                     $q->select('doctor_id')
                       ->from('invoices')
                       ->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->groupBy('doctor_id')
                       ->havingRaw('SUM(amount) <= ?', [$max]);
                 });
             }
     
             // تواريخ الفواتير (من / إلى)
             if (request()->filled('invoice_date_from')) {
                 $query->whereIn('id', function ($q) {
                     $q->select('doctor_id')->from('invoices')
                       ->whereDate('created_at', '>=', request('invoice_date_from'));
                 });
             }
             if (request()->filled('invoice_date_to')) {
                 $query->whereIn('id', function ($q) {
                     $q->select('doctor_id')->from('invoices')
                       ->whereDate('created_at', '<=', request('invoice_date_to'));
                 });
             }
     
             // أنواع الفواتير
             if (request()->filled('invoice_type')) {
                 $needle = match (request('invoice_type')) {
                     'membership' => ['%عضوية%', '%اشتراك%'],
                     'license'    => ['%رخصة%', '%إذن%'],
                     'penalty'    => ['%غرامة%', '%مخالفة%'],
                     'manual'     => ['%يدوية%', '%إضافية%'],
                     default      => [],
                 };
     
                 if ($needle) {
                     $query->whereIn('id', function ($q) use ($needle) {
                         $q->select('doctor_id')->from('invoices')
                           ->where(fn ($s) => $s
                               ->where('description', 'like', $needle[0])
                               ->orWhere('description', 'like', $needle[1] ?? $needle[0]));
                     });
                 }
             }
     
             // عدد الفواتير
             if (request()->filled('min_invoices_count'))
                 $query->has('invoices', '>=', (int) request('min_invoices_count'));
     
             if (request()->filled('max_invoices_count'))
                 $query->has('invoices', '<=', (int) request('max_invoices_count'));
     
             // دَين متأخّر لأكثر من X يوم
             if (request()->filled('overdue_days')) {
                 $days = (int) request('overdue_days');
                 $query->whereIn('id', function ($q) use ($days) {
                     $q->select('doctor_id')->from('invoices')
                       ->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->where('created_at', '<=', now()->subDays($days));
                 });
             }
     
             /* ----- التجميعات ----- */
             $query->withSum(['invoices as total_unpaid' => function ($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                     }], 'amount')
                   ->withSum(['invoices as total_paid' => function ($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::paid);
                     }], 'amount')
                   ->withSum(['invoices as total_relief' => function ($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::relief);
                     }], 'amount')
                   ->withCount('invoices as total_invoices_count')
                   ->withCount(['invoices as unpaid_invoices_count' => function ($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                     }]);
     
             /* ----- الترتيب ----- */
             if (request()->filled('sort_by')) {
                 match (request('sort_by')) {
                     'highest_dues' => $query->orderByDesc('total_unpaid'),
                     'lowest_dues'  => $query->orderBy('total_unpaid'),
                     'most_paid'    => $query->orderByDesc('total_paid'),
                     'most_invoices'=> $query->orderByDesc('total_invoices_count'),
                     default        => $query->orderByDesc('total_unpaid'),
                 };
             } else {
                 $query->orderByDesc('total_unpaid');
             }
         }
     
         /* -------------------------------------------------
          |  تقييد الفرع حسب المنطقة
          * ------------------------------------------------*/
         if (get_area_name() != 'admin' && auth()->user()->branch_id) {
             $query->where('branch_id', auth()->user()->branch_id);
         }
     
         /* -------------------------------------------------
          |  علاقات محمّلة + ترتيب افتراضي
          * ------------------------------------------------*/
         $query->with([
             'branch',
             'doctor_rank',
             'invoices' => fn ($q) => $q
                 ->select('id', 'doctor_id', 'amount', 'status', 'created_at', 'received_at', 'description')
                 ->orderByDesc('created_at')
         ])->orderByDesc('index');
     
         return $without_paginate ? $query->get()
                                   : $query->paginate(10);
     }
     

    public function getRequirements()
    {


        $query = FileType::query()->where('type', 'doctor');
        $specialties = Specialty::all();
        $specialties2 = DB::table('doctors')
        ->select('specialty_2')
        ->whereNotNull('specialty_2')
        ->groupBy('specialty_2')
        ->pluck('specialty_2')
        ->toArray();



        return [
            'branches' => Branch::all(),
            'doctor_ranks' => DoctorRank::where('doctor_type', request('type'))->get(),
            'institutions' => Institution::all(),
            'countries' => Country::all(),
            'universities' => University::all(),
            'academicDegrees' => AcademicDegree::all(),
            'medicalFacilities' => MedicalFacility::all(),
            'specialties' => $specialties,
            'file_types' => $query->get(),
            'specialties2' => $specialties2,
            'doctorRanks' => DoctorRank::where('doctor_type', request('type'))->get(),
            'institutions' => Institution::all(),
        ];
    }

    public function create(array $data)
    {
        // Extract doctor type
        $doctorType = $data['type'];
    
        // Check if doctor already exists
        $doctor = Doctor::where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->where('email', $data['email'])
            ->where('type', $data['type'])
            ->first();
    
        if($doctor) {
            return redirect()->back()->withInput()->withErrors(['هذا الطبيب موجود بالفعل']);
        }
    
        // Check for duplicate index BEFORE creating the doctor
        if(isset($data['index']) && $data['index']) {
            $checkDuplicate = Doctor::where('branch_id', auth()->user()->branch_id)
                ->where('type', $data['type'])
                ->where('index', $data['index'])
                ->first();
    
            if($checkDuplicate) {
                return redirect()->back()->withInput()->withErrors(['هذا الرقم موجود بالفعل في الفرع الحالي']);
            }
        }
    
        DB::beginTransaction();
    
        try {

            if(get_area_name() == "user")
            {
                $data['branch_id'] = auth()->user()->branch_id;
            } else {
                $data['branch_id'] = isset($data['branch_id']) ? $data['branch_id'] : null;
            }
            
            if($data['type'] == "visitor")
            {
                $doctor->medical_facility_id = auth('doctor')->user()->medicalFacility->id ?? null;
                $doctor->branch_id = auth('doctor')->user()->branch_id ?? null;
            }

            // Handle medical facilities
            if(isset($data['ex_medical_facilities'])) {
                $medicalFacilities = $data['ex_medical_facilities'];
                $medicalFacilities = array_filter($medicalFacilities, function($value) {
                    return $value != '-';
                });
                unset($data['ex_medical_facilities']);
            }
    
            // Create doctor with index if provided
            $doctor = Doctor::create($data);
            $doctor->code = null;
            $doctor->institutions()->attach($medicalFacilities ?? []);
            $doctor->membership_status = 'under_approve';
            $doctor->membership_expiration_date = null;
            $doctor->documents_completed = true;
            $doctor->email_verified_at = now();

            if(isset($data['index']) && $data['index']) {
                $doctor->index = $data['index'];
                $doctor->makeCode();

                if($data['last_issued_date']) {
                    $lastIssuedDate = Carbon::parse($data['last_issued_date']);
                    $now = Carbon::now();
                    $validityPeriod = $doctor->type->value == "libyan" ? 12 : 6; 
                    $expirationDate = $lastIssuedDate->copy()->addMonths($validityPeriod);
                    
                    if ($now->greaterThan($expirationDate)) {
                        $doctor->membership_status = "expired";
                        $doctor->membership_expiration_date = $expirationDate;
                    } else {
                        $doctor->membership_status = "active";
                        $doctor->membership_expiration_date = $expirationDate;
                    }
                } else {
                    $doctor->membership_status = "under_payment";
                    $this->createApproveDoctorInvoices($doctor, false);
                }
            }
    
            // Set default password if not provided
            if(!$doctor->password) {
                $doctor->password = Hash::make(123123123);
            }
    
            // Set branch for Libyan doctors
            if($doctor->type->value == "libyan") {
                $doctor->branch_id = auth()->user()->branch_id;
            }
    
            $doctor->save();
            DB::commit();
    
            return $doctor;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }


    public function createApproveDoctorInvoices($doctor, $is_paid)
{
    // إنشاء فاتورة العضوية
    $invoice = new Invoice();
    $invoice->invoice_number = 'INV-' . str_pad(Invoice::count() + 1, 8, '0', STR_PAD_LEFT);
    $invoice->description = "فاتورة عضوية طبيب " . ($doctor->membership_status == "under_payment" ? "جديد" : "تجديد") . " - " . $doctor->name;
    $invoice->user_id = auth()->id();
    $invoice->amount = 0;
    $invoice->status = "unpaid";
    $invoice->doctor_id = $doctor->id;
    $invoice->category = $doctor->membership_status == "under_payment" ? "registration" : "renewal";
    $invoice->branch_id = auth()->user()->branch_id ?? $doctor->branch_id;
    $invoice->branch_id = auth()->user()->branch_id;
    $invoice->save();

    try {
        // فاتورة العضوية
        $pricing_membership = Pricing::where('doctor_type', $doctor->type->value)
                                   ->where('type', 'membership')
                                   ->where('doctor_rank_id', $doctor->doctor_rank_id)
                                   ->first();
        
        if ($pricing_membership) {
            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_membership->name;
            $invoice_item->amount = $pricing_membership->amount;
            $invoice_item->pricing_id = $pricing_membership->id;
            $invoice_item->save();
        }

        // فاتورة البطاقة (للأطباء الجدد فقط)
        if ($doctor->membership_status == "under_payment") {
            $pricing_card = Pricing::where('doctor_type', $doctor->type->value)
                                  ->where('type', 'card')
                                  ->first();

            if ($pricing_card) {
                $invoice_item = new InvoiceItem();
                $invoice_item->invoice_id = $invoice->id;
                $invoice_item->description = $pricing_card->name;
                $invoice_item->amount = $pricing_card->amount;
                $invoice_item->pricing_id = $pricing_card->id;
                $invoice_item->save();
            }
        }

        // تحديث إجمالي الفاتورة
        $invoice->update([
            'amount' => $invoice->items()->sum('amount'),
        ]);

        // إرسال إيميل الموافقة
        try {
            Mail::to($doctor->email)->send(new FirstApproval($doctor, $invoice));
        } catch (Exception $e) {
            Log::warning('Failed to send approval email to doctor: ' . $e->getMessage(), [
                'doctor_id' => $doctor->id,
                'email' => $doctor->email
            ]);
        }


    } catch (\Exception $e) {
        Log::error('Error creating invoice for doctor approval: ' . $e->getMessage(), [
            'doctor_id' => $doctor->id,
            'invoice_id' => $invoice->id
        ]);
        
        // حذف الفاتورة في حالة الخطأ
        $invoice->delete();
        throw $e;
    }

    return $invoice;
}


    public function getVault()
    {
        if(auth()->user()->branch)
        {
            return auth()->user()->branch->vault;
        } else {
            return Vault::first();
        }
    }

    /**
     * Update an existing doctor.
     *
     * @param  Doctor  $doctor
     * @param  array  $data
     * @return Doctor
     */
    public function update(Doctor $doctor, array $data)
    {
        DB::beginTransaction();

        try {

            if(isset($data['password']) && !empty($data['password']))
            {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            

            if(isset($data['phone']))
            {

            }



            $doctor->update($data);

            // Sync the medical facilities
            $doctor->institutions()->sync($data['ex_medical_facilities'] ?? []);



          if(isset($data['documents']))
          {
              // جلب أنواع الملفات للأطباء
              $file_types = FileType::where('type', 'doctor')
                ->where('for_registration', 1)
              ->get();

              // التحقق من وجود الملفات المطلوبة إذا لم تكن موجودة بالفعل
              foreach ($file_types as $file_type) {
                  if ($file_type->is_required) {
                      $existingFile = $doctor->files()->where('file_type_id', $file_type->id)->first();
                      $newFileUploaded = !empty($data['documents'][$file_type->id]);
  
                      if (!$existingFile && !$newFileUploaded) {
                          return redirect()->back()->withInput()->withErrors(["الملف {$file_type->name} مطلوب ولم يتم تحميله."]);
                      }
                  }
              }
  
              foreach ($file_types as $file_type) {
                  if (isset($data['documents'][$file_type->id])) {
                      $file = $data['documents'][$file_type->id];
  
                      $existingFile = $doctor->files()->where('file_type_id', $file_type->id)->first();
  
                      if ($existingFile) {
  
                          $existingFile->update([
                              'file_name' => $file_type->name,
                              'file_path' => $file->store('doctors', 'public'),
                          ]);
                      } else {
                          $doctor->files()->create([
                              'file_name' => $file_type->name,
                              'file_type_id' => $file_type->id,
                              'file_path' => $file->store('doctors', 'public'),
                          ]);
                      }
                  }
              }
          }


            Log::create([
                'user_id' => auth()->user()->id,
                'details' => 'تم تعديل بيانات الطبيب: ' . $doctor->name,
                'loggable_id' => $doctor->id,
                'loggable_type' => Doctor::class,
                'action' => 'update_doctor',
            ]);
            DB::commit();

            return $doctor;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;  // Re-throw the exception after rolling back
        }
    }

    /**
     * Delete a doctor.
     *
     * @param  Doctor  $doctor
     * @return void
     */
    public function delete(Doctor $doctor): void
    {
        DB::beginTransaction();
    
        try {
            // حذف الملفات المرتبطة بالطبيب
            $this->deleteFiles($doctor);
    
            $branchId = $doctor->branch_id;
    
            // حذف الطبيب نفسه
            $doctor->delete();
    
            // تسجيل العملية
            Log::create([
                'user_id' => auth()->id(),
                'details' => 'تم حذف الطبيب: ' . $doctor->name,
                'loggable_id' => $doctor->id,
                'loggable_type' => Doctor::class,
                'action' => 'delete_doctor',
            ]);
    
            
            Artisan::call('fix:doctor-codes');

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    

    /**
     * Handle file deletion for a doctor.
     *
     * @param  Doctor  $doctor
     * @return void
     */
    protected function deleteFiles(Doctor $doctor): void
    {
        $files = [
            'certificate_of_excellence',
            'graduation_date',
            'passport',
            'id_card',
            'employeer_message',
            'id_number',
            'birthـcertificate',
            'personal_photo',
            'work_visa',
            'jobـcontract',
            'anotherـcertificate',
        ];

     
    }


    public function approve(Doctor $doctor)
    {
        DB::beginTransaction();

        try {
            
            if(request('init'))
            {

                $doctor->update([
                    "membership_status" => \App\Enums\MembershipStatus::InActive,
                    'registered_at' => now(),
                ]);

                Log::create([
                    'user_id' => auth()->user()->id,
                    'details' => 'تم الموافقة النهائية على الطبيب: ' . $doctor->name,
                    'loggable_id' => $doctor->id,
                    'loggable_type' => Doctor::class,
                    'action' => 'final_approval',
                ]);



                

                // create invoice
                $this->createInvoice($doctor);
                $doctor->regenerateCode();
                $this->sendFinalApprovalEmail($doctor);

            } else {
                $doctor->update([
                    "visiting_date" => request('meet_date'),
                    "membership_status" => "init_approve",
                ]);

                $this->sendFirstApprovalEmail($doctor);

               
                Log::create([
                    'user_id' => auth()->user()->id,
                    'details' => 'تم الموافقة المبدئية على الطبيب: ' . $doctor->name,
                    'loggable_id' => $doctor->id,
                    'loggable_type' => Doctor::class,
                    'action' => 'initial_approval',
                ]);

            }


            // Log the approval
    

            DB::commit();
        } catch (\Exception $e) {
            // throw error
            throw new \Exception('حدث خطأ أثناء الموافقة على الطبيب. ' . $e->getMessage());
            // return redirect()->back()->withErrors(['حدث خطأ أثناء الموافقة على الطبيب.']);
            DB::rollback();
            throw $e;  // Re-throw the exception after rolling back
        }
    }



    public function reject(Doctor $doctor)
    {
        DB::beginTransaction();

        try {
            
            $doctor->update([
                "membership_status" => \App\Enums\MembershipStatus::Rejected,
                "notes" =>  request('notes'),
            ]);


            Log::create([
                'user_id' => auth()->user()->id,
                'details' => 'تم الرفض على الطبيب: ' . $doctor->name . ' بسبب: ' . request('notes'),
                'loggable_id' => $doctor->id,
                'loggable_type' => Doctor::class,
                'action' => 'reject_doctor',
            ]);


            $this->sendRejectionEmail($doctor,request('notes'));




            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            throw $e;  
        }
    }



    public function sendFinalApprovalEmail($doctor)
    {
        // send email to the doctor
        try {
            Mail::to($doctor->email)
            ->send(new FinalApproval($doctor));
        } catch (\Exception $e) {
            \Log::error('Error sending final approval email: ' . $e->getMessage());
        }
    }

    public function sendFirstApprovalEmail($doctor)
    {
        try {
            Mail::to($doctor->email)
                ->send(new FirstApproval($doctor));
    
        } catch (\Exception $e) {
            \Log::error('Error sending first approval email: ' . $e->getMessage());
        }
    }


        public function sendRejectionEmail($doctor, $reason)
        {
            try {
                // Send email to the doctor
                Mail::to($doctor->email)
                    ->send(new RejectionEmail($doctor, $reason));

            } catch (\Exception $e) {
                \Log::error('Error sending rejection email: ' . $e->getMessage());
            }
        }


        public function initCardID($doctor)
        {
            $doctorType = $doctor->type->value;
            if($doctorType == \App\Enums\DoctorType::Libyan)
            {
                $price = Pricing::find(82);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة العضويه",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ];

                $invoice = Invoice::create($data);
            } else if($doctorType == \App\Enums\DoctorType::Foreign)
            {
                $price = Pricing::find(83);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة العضويه",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ];
                $invoice = Invoice::create($data);

            } else if($doctorType == \App\Enums\DoctorType::Palestinian)
            {
                $price = Pricing::find(84);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة العضويه",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                    'user_id' => auth()->user()->id,
                ];
                $invoice = Invoice::create($data);
            } 
        }
        
}
