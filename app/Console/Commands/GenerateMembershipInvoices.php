<?php

namespace App\Console\Commands;


use Carbon\Carbon;
use App\Models\Doctor;
use App\Models\Invoice;
use App\Models\Pricing;
use App\Enums\DoctorType;
use App\Models\InvoiceItem;
use App\Enums\MembershipStatus;
use Illuminate\Console\Command;  
use Illuminate\Support\Facades\Log; 

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

            // create membership_invoice
            $invoice = new Invoice();
            $invoice->invoice_number = rand(0,999999999);
            $invoice->description = " فاتورة تجديد اشتراك طبيب   " . $doctor->name;
            $invoice->user_id = auth()->id();
            $invoice->amount = 0;
            $invoice->status = "unpaid";
            $invoice->doctor_id = $doctor->id;
            $invoice->category = "registration";
            $invoice->save();


            // registeration invoice 
            $pricing_membership = Pricing::where('doctor_type', $doctor->type->value)->where('type','membership')
            ->where('doctor_rank_id', $doctor->doctor_rank_id)->first();
            
            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_membership->name;
            $invoice_item->amount = $pricing_membership->amount;
            $invoice_item->pricing_id = $pricing_membership->id;

            $invoice_item->save();


            $pricing_licence = Pricing::where("doctor_type", $doctor->type->value)
            ->where('doctor_rank_id', $doctor->doctor_rank_id)->where('type', 'license')
            ->first();

            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_licence->name;
            $invoice_item->amount = $pricing_licence->amount;
            $invoice_item->pricing_id = $pricing_licence->id;
            $invoice_item->save();

            
            $pricing_card_id = Pricing::where('doctor_type', $doctor->type->value)->where('type','card')->first();

            $invoice_item = new InvoiceItem();
            $invoice_item->invoice_id = $invoice->id;
            $invoice_item->description = $pricing_card_id->name;
            $invoice_item->amount = $pricing_card_id->amount;
            $invoice_item->pricing_id = $pricing_card_id->id;
            $invoice_item->save();


            
            $invoice->update([
                'amount' => $invoice->items()->sum('amount'),
            ]);
       
    }
}
