<?php

namespace App\Console\Commands;


use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Enums\MembershipStatus;
use Illuminate\Support\Facades\Log; 
use Illuminate\Console\Command;  
class GenerateMembershipInvoices extends Command
{
    protected $signature = 'generate:membership-invoices';
    protected $description = 'إنشاء فواتير للأطباء الذين تقترب عضوياتهم من الانتهاء.';

    public function handle()
    {
        $daysBeforeExpiration = 90;  
        $currentDate = Carbon::now();
        
        $doctors = Doctor::whereDate('membership_expiration_date', '<=', $currentDate->addDays($daysBeforeExpiration))
            ->where('membership_status', MembershipStatus::Active->value)
            ->get();



        if ($doctors->isEmpty()) {
            $this->info('لا يوجد أطباء يحتاجون إلى إنشاء فواتير.');
            return;
        }

        foreach ($doctors as $doctor) {
            try {
                $this->createInvoice($doctor);
                $this->info("تم إنشاء الفواتير للطبيب: {$doctor->name}");
            } catch (\Exception $e) {
                Log::error("خطأ أثناء إنشاء الفاتورة للطبيب ID {$doctor->id}: " . $e->getMessage());
            }
        }

        $this->info('تم إنشاء جميع الفواتير بنجاح.');
    }

    private function createInvoice($doctor)
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
                throw new \Exception('لا يمكن إضافة طبيب زائر بدون تحديد الالصفة الصحيحة');
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
            throw new \Exception('نوع الطبيب غير موجود لإنشاء الفاتورة.');
        }

        $data = [
            'invoice_number' => "RGS-" . Invoice::latest()->first()->id + 1,
            'invoiceable_id' => $doctor->id,
            'invoiceable_type' => 'App\\Models\\Doctor',
            'description' => "رسوم العضوية الخاصة بالطبيب",
            'amount' => $price->amount ?? 0,
            'pricing_id' => $price->id ?? null,
            'status' => 'unpaid',
            'branch_id' => $doctor->branch_id,
        ];

        $invoice = Invoice::create($data);
    }
}
