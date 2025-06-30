<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Vault;
use App\Models\Invoice;
use App\Models\Licence;
use App\Enums\PricingType;
use App\Mail\FinalApproval;
use App\Models\Transaction;
use App\Models\VaultTransfer;
use App\Services\VaultService;
use App\Enums\MembershipStatus;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InvoiceService
{

   protected $vaultService;

   public function __construct(VaultService $vaultService)
   {
      $this->vaultService = $vaultService;
   }


    /**
     * Mark an invoice as paid.
     *
     * @param Invoice $invoice
     * @return Invoice
     */
    public function markAsPaid(Vault $vault, Invoice $invoice, $notes): Invoice
    {
        $systemStartDate = '2026-01-01';
        $systemStartYear = 2026;
        $currentYear = now()->year;
        $systemStarted = now() >= $systemStartDate;
        
        if ($systemStarted && $currentYear > $systemStartYear) {
            $requiredYear = $currentYear - 1;
            
            $yearly_transfer = VaultTransfer::where('to_vault_id', 1)
                ->whereYear('created_at', $requiredYear)
                ->first();
            
            if (is_null($yearly_transfer)) {
                throw new \Exception("لا يمكن استقبال الأموال قبل تحويل القيمة السنوية المطلوبة للسنة {$requiredYear}");
            }
        }
        
        // تحديث حالة الفاتورة
        $invoice->status = 'paid';
        $invoice->received_at = now();
        $invoice->received_by = Auth::id();
        $invoice->save();
    
        if($invoice->category == "registration")
        {
            if($invoice->visitor)
            {
                $invoice->visitor->setSequentialIndex();
                $invoice->visitor->makeCode();
                $invoice->visitor->membership_status = "active";
                $invoice->visitor->membership_expiration_date = $invoice->visitor->type->value == "foreign" ? 
                    Carbon::now()->addMonths(6) : Carbon::now()->addMonths(12);
                $invoice->visitor->save();
    
                // new license 
                $license = new Licence();
                $license->workin_medical_facility_id = $invoice->visitor->medical_facility_id;
                $license->doctor_id = $invoice->visitor->id;
                $license->doctor_type = $invoice->visitor->type->value;
                $license->issued_date = $invoice->visitor->visit_from;
                $license->expiry_date = $invoice->visitor->visit_to;
                $license->status = "active";
                $license->created_by = auth()->id();
                $license->amount = 0; 
                $license->branch_id = auth()->user()->branch_id;
                $license->save();
            } else {
                $invoice->doctor->setSequentialIndex();
                $invoice->doctor->makeCode();
                $invoice->doctor->membership_status = "active";
                $invoice->doctor->membership_expiration_date = $invoice->doctor->type->value == "foreign" ? 
                    Carbon::now()->addMonths(6) : Carbon::now()->addMonths(12);
                $invoice->doctor->save();
            }
    
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = " فاتورة عضوية طبيب جديد  " . $invoice->doctor->name . " رقم الفاتورة  " . $invoice->invoice_number;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 1;
            $transaction->save();
    
            try {
                Mail::to($invoice->doctor->email)->send(new FinalApproval($invoice->doctor));
            } catch (\Exception $e) {
                \Log::error('Failed to send final approval email: ' . $e->getMessage());
            }
        }
    
        if($invoice->category == "medical_facility_registration")
        {
            $medicalFacility = $invoice->doctor->medicalFacility;
            $medicalFacility->membership_status = "active";
            $medicalFacility->membership_expiration_date = now()->addYear();
            $medicalFacility->save();
    
            // create license
            $license = new Licence();
            $license->medical_facility_id = $medicalFacility->id;
            $license->issued_date = now();
            $license->expiry_date = now()->addYear();
            $license->status = "active";
            $license->created_by = auth()->id();
            $license->amount = 0; 
            $licence->branch_id = auth()->user()->branch_id;
            $license->save();
    
            // Create a transaction for the payment
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = " فاتورة عضوية منشأة طبية جديدة  " . $medicalFacility->name;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 1;
            $transaction->save();
        }
    
        if($invoice->category == "medical_facility_renew")
        {
            $medicalFacility = $invoice->doctor->medicalFacility;
            $medicalFacility->membership_status = "active";
            $medicalFacility->membership_expiration_date = now()->addYear();
            $medicalFacility->save();
    
            // create license
            $license = new Licence();
            $license->medical_facility_id = $medicalFacility->id;
            $license->issued_date = now();
            $license->expiry_date = now()->addYear();
            $license->status = "active";
            $license->created_by = auth()->id();
            $license->amount = 0; 
            $license->branch_id = auth()->user()->branch_id;
            $license->save();
    
            // Create a transaction for the payment
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = " فاتورة تجديد منشأة طبية  " . $medicalFacility->name . " رقم الفاتورة  " . $invoice->invoice_number;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 1;
            $transaction->save();
        }
    
        if($invoice->category == "licence")
        {
            $licence = $invoice->licence;
            $licence->status = "active";
            $licence->save();
    
            // Create a transaction for the payment
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = 'فاتورة أذن مزاولة جديد للطبيب ' . $licence->doctor->name . ' كود الاذن ' . $invoice->licence->code; 
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 2;
            $transaction->save();
        }
    
        if($invoice->category == "doctor_mail")
        {
            $doctorMail = $invoice->doctorMail;
            $doctorMail->status = "under_proccess";
            $doctorMail->save();
    
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = "فاتورة طلب اوراق خارجية #" . $doctorMail->id;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            $transaction->financial_category_id = 3;
            $transaction->save();
        }



        if($invoice->category == "custom")
        {
            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = $invoice->description;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            // $transaction->financial_category_id = 3;
            $transaction->save();
        }



        if($invoice->category == "card")
        {

            $doctor = $invoice->doctor;
            $doctor->card_expiration_date = now()->addYear();
            $doctor->save();

            $vault = auth()->user()->vault ?? Vault::first();
            $vault->balance += $invoice->amount;
            $vault->save();
    
            $transaction = new Transaction();
            $transaction->amount = $invoice->amount;
            $transaction->user_id = auth()->id();
            $transaction->vault_id = $vault->id;
            $transaction->type = "deposit";
            $transaction->desc = $invoice->description;
            $transaction->branch_id = auth()->user()->branch_id;
            $transaction->balance = $vault->balance;
            // $transaction->financial_category_id = 3;
            $transaction->save();
        }


    
        return $invoice;
    }
    /**
     * Mark an invoice as relief.
     *
     * @param Invoice $invoice
     * @param string $reason
     * @return Invoice
     */


    public function markAsRelief(Invoice $invoice, string $reason): Invoice
    {
        $invoice->status = 'relief';
        $invoice->relief_at = now();
        $invoice->relief_by = Auth::id();
        $invoice->relief_reason = $reason;
        $invoice->save();
        $this->runMembership($invoice);
        return $invoice;
        
    }

    /**
     * Restore a soft-deleted invoice.
     *
     * @param Invoice $invoice
     * @return void
     */
    public function restoreInvoice(Invoice $invoice): void
    {
        $invoice->restore();
    }

    /**
     * Update invoice amount.
     *
     * @param Invoice $invoice
     * @param float $amount
     * @return Invoice
     */
    public function updateAmount(Invoice $invoice, float $amount): Invoice
    {
        $invoice->amount = $amount;
        $invoice->save();

        return $invoice;
    }

    /**
     * Fetch all paid invoices.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getPaidInvoices()
    {
        return Invoice::paid()->get();
    }

    /**
     * Fetch all unpaid invoices.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getUnpaidInvoices()
    {
        return Invoice::unpaid()->get();
    }


    public function runMembership(Invoice $invoice)
    {

        $membership = $invoice->invoiceable;
        $membership->membership_status = MembershipStatus::Active;
        $membership->membership_expiration_date = now()->addYear();
        $membership->save();

    }
}
