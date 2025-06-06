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
     
         // الفلاتر الأساسية الموجودة
         if (request()->filled('branch_id')) {
             $query->where('branch_id', request('branch_id'));
         }
     
         if (request()->filled('doctor_number')) {
             $query->where('doctor_number', request('doctor_number'));
         }
     
         if (request()->filled('name')) {
             $query->where('name', 'like', '%' . request('name') . '%');
         }
     
         if (request()->filled('phone')) {
             $query->where('phone', 'like', '%' . request('phone') . '%');
         }
     
         if (request()->filled('email')) {
             $query->where('email', 'like', '%' . request('email') . '%');
         }
     
         if (request()->filled('registered_at')) {
             $query->whereDate('registered_at', request('registered_at'));
         }
     
         if (request()->filled('membership_status')) {
             $query->where('membership_status', request('membership_status'));
         }
     
         if (request()->filled('last_license_status')) {
             $query->whereHas('licenses', function ($q) {
                 $q->where('status', request('last_license_status'));
             });
         }
     
         if (request()->filled('specialization')) {
             $query->where('specialty_1_id', request('specialization'));
         }
     
         if (request()->filled('national_number')) {
             $query->where('national_number', 'like', '%' . request('national_number') . '%');
         }
     
         if(request('doctor_rank_id')) {
             $query->where('doctor_rank_id', request('doctor_rank_id'));
         }
     
         if (request()->filled('passport_number')) {
             $query->where('passport_number', 'like', '%' . request('passport_number') . '%');
         }
     
         if (request()->filled('academic_degree')) {
             $query->where('academic_degree_id', request('academic_degree'));
         }
     
         // Type filters
         if (request('type') === 'libyan') {
             $query->where('type', 'libyan');
         } elseif (request('type') === 'palestinian') {
             $query->where('type', 'palestinian');
         } elseif (request('type') === 'visitor') {
             $query->where('type', 'visitor');
         } elseif (request('type') === 'foreign') {
             $query->where('type', 'foreign');
         }
     
         if (request()->filled('banned')) {
             $query->where('membership_status', 'banned');
         }
     
         // ***** الفلاتر المالية الجديدة للمدير المالي *****
         if (get_area_name() == "finance") {
             
             // فلتر حالة الدفع
             if (request()->filled('payment_status')) {
                 switch (request('payment_status')) {
                     case 'has_unpaid':
                         // الأطباء الذين لديهم مستحقات غير مدفوعة
                         $query->whereHas('invoices', function($q) {
                             $q->where('status', \App\Enums\InvoiceStatus::unpaid)
                               ->havingRaw('SUM(amount) > 0');
                         });
                         break;
                         
                     case 'no_dues':
                         // الأطباء الذين ليس لديهم مستحقات
                         $query->whereDoesntHave('invoices', function($q) {
                             $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                         });
                         break;
                         
                     case 'overpaid':
                         // الأطباء الذين دفعوا أكثر من المطلوب (نادر)
                         $query->whereHas('invoices', function($q) {
                             $q->selectRaw('
                                 SUM(CASE WHEN status = "paid" THEN amount ELSE 0 END) as paid_total,
                                 SUM(CASE WHEN status = "unpaid" THEN amount ELSE 0 END) as unpaid_total
                             ')
                             ->havingRaw('paid_total > unpaid_total');
                         });
                         break;
                 }
             }
     
             // فلتر الحد الأدنى للمستحقات
             if (request()->filled('min_amount')) {
                 $minAmount = (float) request('min_amount');
                 $query->whereHas('invoices', function($q) use ($minAmount) {
                     $q->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->havingRaw('SUM(amount) >= ?', [$minAmount]);
                 });
             }
     
             // فلتر الحد الأقصى للمستحقات
             if (request()->filled('max_amount')) {
                 $maxAmount = (float) request('max_amount');
                 $query->whereHas('invoices', function($q) use ($maxAmount) {
                     $q->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->havingRaw('SUM(amount) <= ?', [$maxAmount]);
                 });
             }
     
             // فلتر تاريخ الفاتورة من
             if (request()->filled('invoice_date_from')) {
                 $query->whereHas('invoices', function($q) {
                     $q->whereDate('created_at', '>=', request('invoice_date_from'));
                 });
             }
     
             // فلتر تاريخ الفاتورة إلى
             if (request()->filled('invoice_date_to')) {
                 $query->whereHas('invoices', function($q) {
                     $q->whereDate('created_at', '<=', request('invoice_date_to'));
                 });
             }
     
             // فلتر الفرع (للمدير المالي المركزي)
             if (request()->filled('branch_id') && auth()->user()->can('view_all_branches')) {
                 $query->where('branch_id', request('branch_id'));
             }
     
             // فلتر نوع الفاتورة
             if (request()->filled('invoice_type')) {
                 switch (request('invoice_type')) {
                     case 'membership':
                         $query->whereHas('invoices', function($q) {
                             $q->where('description', 'like', '%عضوية%')
                               ->orWhere('description', 'like', '%اشتراك%');
                         });
                         break;
                         
                     case 'license':
                         $query->whereHas('invoices', function($q) {
                             $q->where('description', 'like', '%رخصة%')
                               ->orWhere('description', 'like', '%إذن%');
                         });
                         break;
                         
                     case 'penalty':
                         $query->whereHas('invoices', function($q) {
                             $q->where('description', 'like', '%غرامة%')
                               ->orWhere('description', 'like', '%مخالفة%');
                         });
                         break;
                         
                     case 'manual':
                         $query->whereHas('invoices', function($q) {
                             $q->where('description', 'like', '%يدوية%')
                               ->orWhere('description', 'like', '%إضافية%');
                         });
                         break;
                 }
             }
     
             // فلتر تاريخ آخر دفعة
             if (request()->filled('last_payment_from')) {
                 $query->whereHas('invoices', function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::paid)
                       ->whereDate('received_at', '>=', request('last_payment_from'));
                 });
             }
     
             if (request()->filled('last_payment_to')) {
                 $query->whereHas('invoices', function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::paid)
                       ->whereDate('received_at', '<=', request('last_payment_to'));
                 });
             }
     
             // فلتر حسب المستخدم الذي أنشأ الفاتورة
             if (request()->filled('created_by_user')) {
                 $query->whereHas('invoices', function($q) {
                     $q->where('user_id', request('created_by_user'));
                 });
             }
     
             // فلتر حسب حالة الإعفاء
             if (request()->filled('relief_status')) {
                 if (request('relief_status') === 'has_relief') {
                     $query->whereHas('invoices', function($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::relief);
                     });
                 } elseif (request('relief_status') === 'no_relief') {
                     $query->whereDoesntHave('invoices', function($q) {
                         $q->where('status', \App\Enums\InvoiceStatus::relief);
                     });
                 }
             }
     
             // فلتر حسب عدد الفواتير
             if (request()->filled('min_invoices_count')) {
                 $minCount = (int) request('min_invoices_count');
                 $query->has('invoices', '>=', $minCount);
             }
     
             if (request()->filled('max_invoices_count')) {
                 $maxCount = (int) request('max_invoices_count');
                 $query->has('invoices', '<=', $maxCount);
             }
     
             // فلتر الأطباء الذين لم يدفعوا لفترة طويلة
             if (request()->filled('overdue_days')) {
                 $overdueDays = (int) request('overdue_days');
                 $query->whereHas('invoices', function($q) use ($overdueDays) {
                     $q->where('status', \App\Enums\InvoiceStatus::unpaid)
                       ->where('created_at', '<=', now()->subDays($overdueDays));
                 });
             }
     
             // إضافة الحسابات المالية للنتائج
             $query->withSum([
                 'invoices as total_unpaid' => function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                 }
             ], 'amount')
             ->withSum([
                 'invoices as total_paid' => function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::paid);
                 }
             ], 'amount')
             ->withSum([
                 'invoices as total_relief' => function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::relief);
                 }
             ], 'amount')
             ->withCount([
                 'invoices as total_invoices_count'
             ])
             ->withCount([
                 'invoices as unpaid_invoices_count' => function($q) {
                     $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                 }
             ]);
     
             // ترتيب خاص بالمدير المالي
             if (request()->filled('sort_by')) {
                 switch (request('sort_by')) {
                     case 'highest_dues':
                         $query->orderByDesc('total_unpaid');
                         break;
                     case 'lowest_dues':
                         $query->orderBy('total_unpaid');
                         break;
                     case 'most_paid':
                         $query->orderByDesc('total_paid');
                         break;
                     case 'most_invoices':
                         $query->orderByDesc('total_invoices_count');
                         break;
                     case 'oldest_dues':
                         $query->whereHas('invoices', function($q) {
                             $q->where('status', \App\Enums\InvoiceStatus::unpaid);
                         })->orderBy(
                             \DB::table('invoices')
                                 ->select('created_at')
                                 ->whereColumn('doctor_id', 'doctors.id')
                                 ->where('status', \App\Enums\InvoiceStatus::unpaid)
                                 ->orderBy('created_at')
                                 ->limit(1)
                         );
                         break;
                     default:
                         $query->orderByDesc('total_unpaid');
                 }
             } else {
                 // الترتيب الافتراضي للمدير المالي: حسب المستحقات
                 $query->orderByDesc('total_unpaid');
             }
     
         } else {
             // إذا لم يكن المدير المالي، أظهر فقط الأطباء الذين لديهم فواتير غير مدفوعة
             // $query->whereHas('invoices', function($q) {
             //     $q->where('status', 'unpaid');
             // });
         }
     
         // Area-based branch restriction
         if (in_array(get_area_name(), ['user','finance'])) {
             $query->where('branch_id', auth()->user()->branch_id);
         }
     
        $query = $query->with([
             'branch',
             'doctor_rank',
             'invoices' => function($q) {
                 // تحميل الفواتير مع التفاصيل المطلوبة
                 $q->select('id', 'doctor_id', 'amount', 'status', 'created_at', 'received_at', 'description')
                   ->orderByDesc('created_at');
             }
         ])
         ->orderByDesc('id');

         if($without_paginate)
         {
            $query = $query->get();
         } else {
            $query = $query->paginate(10);
         }

         return $query;
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


        // استخراج نوع الطبيب من البيانات
        $doctorType = $data['type'];

      

        // check the doctor is exists before
        $doctor = Doctor::where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->where('email', $data['email'])
            ->where('type', $data['type'])
            ->first();

        if($doctor)
        {
            return redirect()->back()->withInput()->withErrors(['هذا الطبيب موجود بالفعل']);
        }
        

        DB::beginTransaction(); 

        try {
            
            $data['branch_id'] = auth()->user()->branch_id;


            if(isset($data['ex_medical_facilities']))
            {
                $medicalFacilities = $data['ex_medical_facilities'];
                $medicalFacilities = array_filter($medicalFacilities, function($value) {
                    return $value != '-';
                });
                unset($data['ex_medical_facilities']);
            } 


            $doctor = Doctor::create($data);
            $doctor->code = null;
            $doctor->index = null;
            $doctor->institutions()->attach($medicalFacilities ?? []);
            $doctor->membership_status = 'under_approve';
            $doctor->membership_expiration_date = null;
            $doctor->save();

            if($data['index'])
            {
                $checkDuplicate = Doctor::where('branch_id', auth()->user()->branch_id)
                ->where('index', $data['index'])
                ->first();


                if($checkDuplicate)
                {
                    throw new \Exception('هذا الرقم موجود بالفعل في الفرع الحالي');
                }

                $doctor->index = $data['index'];
                $doctor->makeCode();

                

                if($data['last_issued_date'] && $data['license_number'] )
                {

                    // check duplicate
                    $checkDuplicate = Licence::where('doctor_id', $doctor->id)
                        ->where('index', $data['license_number'])
                        ->first();

                    if($checkDuplicate)
                    {
                        throw new \Exception('هذا الرقم موجود بالفعل في الفرع الحالي');
                    }

                    

                    $doctor->membership_status = "active";
                    $doctor->membership_expiration_date = Carbon::parse($data['last_issued_date'])->addYear();
                    $doctor->save();

                    // create license 
                    $licence = new Licence();
                    $licence->doctor_id = $doctor->id;
                    $licence->doctor_type = $doctor->type->value;
                    $licence->issued_date = $data['last_issued_date'];
                    $licence->expiry_date = Carbon::parse($data['last_issued_date'])->addYear();
                    $licence->status = "active";
                    $licence->doctor_rank_id = $doctor->doctor_rank_id;
                    $licence->created_by = auth()->id();
                    $licence->amount = 0;
                    $licence->save();

                    $year = Carbon::parse($data['last_issued_date'])->year;
                    $licence->update([
                        'index' => $data['license_number'],
                        'code' => "LIC-LIB-${year}-{$licence->index}",
                    ]);


                }
            }


            if(!$doctor->password)
            {
                $doctor->password = Hash::make($doctor->phone);
            }


            if($doctor->type->value == "libyan")
            {
                $doctor->branch_id = auth()->user()->branch_id;
            }
            $doctor->save();



   
            DB::commit();

            return $doctor;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e; // إعادة إلقاء الاستثناء بعد التراجع عن العملية
        }
    }


    public function createInvoice($doctor)
    {



        $pricing = Pricing::where('type', 'membership')->where('doctor_rank_id', $doctor->doctor_rank_id)
        ->where('doctor_type', $doctor->type->value)->first();


       
        $data = [
            'invoice_number' => "RGS-" . Invoice::count() + 1,
            'invoiceable_id' => $doctor->id,
            'invoiceable_type' => 'App\Models\Doctor',
            'description' => $pricing->name,
            'user_id' => auth()->id(),
            'amount' => $pricing->amount,
            'pricing_id' => $pricing->id,
            'status' => 'unpaid',
            'branch_id' => auth()->user()->branch_id,
            'user_id' => auth()->user()->id,
        ];


        $invoice = Invoice::create($data);



        $open_file = Pricing::where('category', 'open_file')->where('doctor_type', $doctor->type->value)->first();

        if(isset($open_file))
        {
            $data = [
                'invoice_number' => "FIL-" . Invoice::latest()->first()->id + 1,
                'invoiceable_id' => $doctor->id,
                'invoiceable_type' => 'App\Models\Doctor',
                'description' => $open_file->name,
                'user_id' => auth()->id(),
                'amount' => $open_file->amount,
                'pricing_id' => $open_file->id,
                'status' => 'unpaid',
                'branch_id' => auth()->user()->branch_id,
                'user_id' => auth()->user()->id,
            ];
    
    
            $invoice = Invoice::create($data);
            // $this->invoiceService->markAsPaid($this->getVault(), $invoice, $invoice->description);
        }

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
                              'file_name' => $file->getClientOriginalName(),
                              'file_path' => $file->store('doctors', 'public'),
                          ]);
                      } else {
                          $doctor->files()->create([
                              'file_name' => $file->getClientOriginalName(),
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
        Mail::to($doctor->email)
        ->send(new FinalApproval($doctor));
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
