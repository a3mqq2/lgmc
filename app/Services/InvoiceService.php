<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Vault;
use App\Models\Invoice;
use App\Models\Licence;
use App\Enums\PricingType;
use App\Models\Transaction;
use App\Enums\MembershipStatus;
use Illuminate\Support\Facades\Auth;

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
    public function markAsPaid(Vault $vault, Invoice $invoice,$notes): Invoice
    {
        $invoice->status = 'paid';
        $invoice->received_at = now();
        $invoice->received_by = Auth::id();
        $invoice->save();


        
        if($invoice->category == "registration")
        {
            $invoice->doctor->membership_status = "active";
            $invoice->doctor->membership_expiration_date = $invoice->doctor->type->value == "foreign" ?   Carbon::now()->addMonths(6) : Carbon::now()->addMonths(12);
            $invoice->doctor->setSequentialIndex();
            $invoice->doctor->makeCode();
            $invoice->doctor->save();


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
            $transaction->save();


            $licence = new Licence();
            $licence->doctor_id = $invoice->doctor->id;
            $licence->doctor_type = $invoice->doctor->type->value;
            $licence->issued_date = now();
            $licence->expiry_date = $invoice->doctor->type->value == "foreign" ? now()->addMonths(6) : now()->addMonths(12);
            $licence->status = "active";
            $licence->doctor_rank_id = $invoice->doctor->doctor_rank_id;
            $licence->created_by = auth()->id();
            $licence->amount = 0;
            $licence->save();
        }


        if($invoice->category == "medical_facility_registration" )
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
