<?php

namespace App\Services;

use App\Models\Log;
use App\Models\Vault;
use App\Models\Branch;
use App\Models\Doctor;
use App\Models\Country;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Models\FileType;
use App\Enums\DoctorType;
use App\Models\Blacklist;
use App\Models\Specialty;
use App\Models\DoctorRank;
use App\Models\University;
use App\Mail\FinalApproval;
use App\Mail\FirstApproval;
use App\Mail\RejectionEmail;
use App\Models\AcademicDegree;
use App\Models\MedicalFacility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
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

     public function getDoctors()
     {
         $query = Doctor::query();
         
         if (request()->filled('branch_id')) {
             $query->where('branch_id', request('branch_id'));
         }
     
         if (request('name')) {
             $query->where('name', 'like', '%' . request('name') . '%');
         }
     
         if (request('phone')) {
             $query->where('phone', 'like', '%' . request('phone') . '%');
         }
     
         if (request('email')) {
             $query->where('email', 'like', '%' . request('email') . '%');
         }
     
         if (request('national_number')) {
             $query->where('national_number', 'like', '%' . request('national_number') . '%');
         }
     
         if (request('passport_number')) {
             $query->where('passport_number', 'like', '%' . request('passport_number') . '%');
         }
     
         if (request('academic_degree')) {
             $query->where('academic_degree_id', request('academic_degree'));
         }
     
         if (request('type') == "libyan") {
             $query->where('type', 'libyan');
         }
     
         if (request('type') == "palestinian") {
             $query->where('type', 'palestinian');
         }
     
         if (request('type') == "visitor") {
             $query->where('type', 'visitor');
         }
     
         if (request('type') == "foreign") {
             $query->where('type', 'foreign');
         }
     

         if(request('regestration'))
         {
            $query->where('membership_status','pending');
         } else if(request('init_approve')) {
            $query->where('membership_status','init_approve');
         } else if(request('rejection')) {
            $query->where('membership_status','rejected');
         } else   {
            $query->where('membership_status','!=','pending');
         }


         if (get_area_name() == "user" || get_area_name() == "finance") {
             $query->where('branch_id', auth()->user()->branch_id);
         }
     
         if (get_area_name() == "finance") {
             $query->withCount(['invoices as total_unpaid_invoices' => function ($q) {
                 $q->where('status', \App\Enums\InvoiceStatus::unpaid);
             }])->orderByDesc('total_unpaid_invoices');
         }
     
         return $query->with('branch')->paginate(50);
     }
     

    public function getRequirements()
    {


        $query = FileType::query()->where('type', 'doctor')->where('doctor_type', request('type'));
        $specialties = Specialty::where('specialty_id', null)->get();

        return [
            'branches' => Branch::all(),
            'doctor_ranks' => DoctorRank::all(),
            'medical_facilities' => MedicalFacility::all(),
            'countries' => Country::all(),
            'universities' => University::all(),
            'academicDegrees' => AcademicDegree::all(),
            'medicalFacilities' => MedicalFacility::all(),
            'specialties' => $specialties,
            'file_types' => $query->get(),
        ];
    }

    public function create(array $data): Doctor
    {


        // استخراج نوع الطبيب من البيانات
        $doctorType = $data['type'];

        // بناء استعلام التحقق من البلاك ليست بناءً على نوع الطبيب
        $blacklistQuery = Blacklist::where('doctor_type', $doctorType)
            ->where(function ($query) use ($data, $doctorType) {
                $query->where('name', $data['name'])
                      ->orWhere('number_phone', $data['phone']);
                
                if ($doctorType === 'libyan') {
                    // تحقق من رقم الهوية الوطنية لليبيا
                    $query->orWhere('id_number', $data['national_number']);
                } else {
                    // تحقق من رقم الجواز للأطباء غير الليبيين
                    $query->orWhere('passport_number', $data['passport_number']);
                }
            });

        // إذا كان الطبيب موجودًا في البلاك ليست، إلقاء استثناء
        if ($blacklistQuery->exists()) {
            return redirect()->back()->withInput()->withErrors(['هذا الطبيب موجود في البلاك ليست ولا يمكن إضافته.']);
        }


        // check the doctor is exists before
        $doctor = Doctor::where('name', $data['name'])
            ->where('phone', $data['phone'])
            ->where('email', $data['email'])
            ->where('passport_number', $data['passport_number'])
            ->where('type', $data['type'])
            ->first();

        if($doctor)
        {
            return redirect()->back()->withInput()->withErrors(['هذا الطبيب موجود بالفعل']);
        }
        

        DB::beginTransaction(); 

        try {
            
            // توليد رمز الطبيب
            $data['branch_id'] = get_area_name() == "admin" ? $data['branch_id'] : auth()->user()->branch_id;
            // $data['date_of_birth'] = isset($data['birth_year'], $data['month'], $data['day']) 
            // ? "{$data['birth_year']}-".str_pad($data['month'], 2, '0', STR_PAD_LEFT)."-".str_pad($data['day'], 2, '0', STR_PAD_LEFT)
            // : null;

            if(!isset($data['branch_id']))
            {
                return redirect()->back()->withInput()->withErrors(['يجب تحديد الفرع']);
            }


            if(isset($data['ex_medical_facilities']))
            {
                $medicalFacilities = $data['ex_medical_facilities'];
                $medicalFacilities = array_filter($medicalFacilities, function($value) {
                    return $value != '-';
                });
                unset($data['ex_medical_facilities']);
            } 


            // إنشاء السجل الجديد للطبيب
            $data['password'] = Hash::make($data['password']);

            // 

            $doctor = Doctor::create($data);

            // ربط المرافق الطبية
            $doctor->medicalFacilities()->attach($medicalFacilities ?? []);
            $doctor->code = Doctor::max('id') + 1;
            // تحديث رمز الطبيب بناءً على الفرع
            $doctor->membership_status = 'inactive';
            $doctor->membership_expiration_date = null;
            $doctor->save();

            // جلب أنواع الملفات للأطباء
            $file_types = FileType::where('type', 'doctor')
                ->where('doctor_type', $data['type'])
                ->get();

            // التحقق من وجود الملفات المطلوبة
            foreach ($file_types as $file_type) {
                if ($file_type->is_required && empty($data['documents'][$file_type->id])) {
                    return redirect()->back()->withInput()->withErrors(["الملف {$file_type->name} مطلوب ولم يتم تحميله."]);
                }
            }

            // معالجة ملفات المستندات
            foreach ($file_types as $file_type) {
                if (isset($data['documents'][$file_type->id])) {
                    $file = $data['documents'][$file_type->id];
                    $path = $file->store('doctors','public');
                    $doctor->files()->create([
                        'file_name' => $file->getClientOriginalName(),
                        'file_type_id' => $file_type->id,
                        'file_path' => $path,
                    ]);
                }
            }

            // إنشاء الفاتورة الخاصة بالطبيب
            $this->createInvoice($doctor);

            // initize invoice for issued the id card

            $this->initCardID($doctor);


            // تسجيل العملية في السجل
            Log::create([
                'user_id' => auth()->user()->id,
                'details' => 'تم إنشاء طبيب جديد: ' . $doctor->name,
            ]);

            DB::commit();

            return $doctor;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e; // إعادة إلقاء الاستثناء بعد التراجع عن العملية
        }
    }


    public function createInvoice($doctor)
    {




        
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
                return redirect()->back()->withInput()->withErrors(['لا يمكن اضافة طبيب زائر بدون تحديد الرتبة الصحيحة']);
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
            FacadesLog::error('Doctor Type is not exists to create an register invoice');
       }


       
        $data = [
            'invoice_number' => "RGS-" . Invoice::count() + 1,
            'invoiceable_id' => $doctor->id,
            'invoiceable_type' => 'App\Models\Doctor',
            'description' => "رسوم العضوية الخاصة بالطبيب",
            'user_id' => auth()->id(),
            'amount' => $price->amount,
            'pricing_id' => $price->id,
            'status' => 'unpaid',
            'branch_id' => auth()->user()->branch_id,
        ];


        $invoice = Invoice::create($data);
        // $this->invoiceService->markAsPaid($this->getVault(), $invoice, $invoice->description);



        if($doctor->type == DoctorType::Libyan)
        {
            $price = Pricing::find(79);
        } else if ($doctor->type == DoctorType::Palestinian) 
        {
            $price = Pricing::find(80);
        } else if ($doctor->type == DoctorType::Foreign)  {
            $price = Pricing::find(81);
        }

        if(isset($price))
        {
            $data = [
                'invoice_number' => "FIL-" . Invoice::latest()->first()->id + 1,
                'invoiceable_id' => $doctor->id,
                'invoiceable_type' => 'App\Models\Doctor',
                'description' => "رسوم فتح ملف للطبيب",
                'user_id' => auth()->id(),
                'amount' => $price->amount,
                'pricing_id' => $price->id,
                'status' => 'unpaid',
                'branch_id' => auth()->user()->branch_id,
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
    public function update(Doctor $doctor, array $data): Doctor
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
            $doctor->medicalFacilities()->sync($data['medical_facilities'] ?? []);

            // Log the update
            Log::create([
                'user_id' => auth()->user()->id,
                'details' => 'تم تعديل بيانات الطبيب: ' . $doctor->name,
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
            // Delete the doctor's associated files (if any)
            $this->deleteFiles($doctor);

            // Delete the doctor
            $doctor->delete();

            // Log the deletion
            Log::create([
                'user_id' => auth()->user()->id,
                'details' => 'تم حذف الطبيب: ' . $doctor->name,
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;  // Re-throw the exception after rolling back
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
            'graduationـcertificate',
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

        foreach ($files as $file) {
            if ($doctor->$file) {
                Storage::delete($doctor->$file);
            }
        }
    }


    public function approve(Doctor $doctor)
    {
        DB::beginTransaction();

        try {
            
            if(request('init'))
            {

                $doctor->update([
                    "membership_status" => \App\Enums\MembershipStatus::InActive,
                ]);

                Log::create([
                    'user_id' => auth()->user()->id,
                    'details' => 'تم الموافقة النهائية على الطبيب: ' . $doctor->name,
                ]);


                $doctor->code = Doctor::max('code') + 1;

                // create invoice
                $this->createInvoice($doctor);
                $this->generateCode($doctor);
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
                ]);
            }


            // Log the approval
    

            DB::commit();
        } catch (\Exception $e) {
            dd($e->getMessage());
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
                'details' => 'تم  الرفض على الطبيب: ' . $doctor->name . " وذلك بسبب :  " . request('notes'),
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
            $doctorType = $doctor->type;
            if($doctorType == \App\Enums\DoctorType::Libyan)
            {
                $price = Pricing::find(82);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة الهوية",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                ];

                $invoice = Invoice::create($data);
            } else if($doctorType == \App\Enums\DoctorType::Foreign)
            {
                $price = Pricing::find(83);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة الهوية",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                ];
                $invoice = Invoice::create($data);

            } else if($doctorType == \App\Enums\DoctorType::Palestinian)
            {
                $price = Pricing::find(84);
                $data = [
                    'invoice_number' => "ID-" . Invoice::latest()->first()->id + 1,
                    'invoiceable_id' => $doctor->id,
                    'invoiceable_type' => 'App\Models\Doctor',
                    'description' => "رسوم إصدار بطاقة الهوية",
                    'user_id' => auth()->id(),
                    'amount' => $price->amount,
                    'pricing_id' => $price->id,
                    'status' => 'unpaid',
                    'branch_id' => auth()->user()->branch_id,
                ];
                $invoice = Invoice::create($data);
            } 
        }


        public function generateCode($doctor)
        {
            dd(Doctor::max('code') );
            $doctor->code = Doctor::max('code') + 1;
            $doctor->save();
        }
}
