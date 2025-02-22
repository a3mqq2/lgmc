<?php

namespace App\Services;

use App\Enums\MembershipStatus;
use App\Enums\PricingType;
use App\Models\Invoice;
use App\Models\Licence;
use App\Models\Vault;
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

      //   create transaction in the vault

        $transaction_type_id = $invoice->transaction_type_id ? $invoice->transaction_type_id : null;
        if($invoice->pricing)
        {

            
            if($invoice->pricing->type == PricingType::Membership)
            {
                $this->runMembership($invoice);
                $transaction_type_id = 1;
            } else if($invoice->pricing->type == PricingType::License)
            {
                $transaction_type_id = 2;

                if($invoice->licence && ($invoice->licence->status == "under_approve_admin" || $invoice->licence->status == "under_payment"))
                {   
                    $invoice->licence->status = "active";
                    $invoice->licence->save();
                }
          
            } else if($invoice->pricing->type == PricingType::Service)
            {
                $transaction_type_id = 4;
            } else if($invoice->pricing->type == PricingType::Penalty)
            {
                $transaction_type_id = 4;
            }
        }
      

      $this->vaultService->incrementBalance($vault, $invoice->amount);
      $this->vaultService->createTransaction([
            'vault_id' => $vault->id,
            'amount' => $invoice->amount,
            'type' => 'deposit',
            'transaction_type_id' => $transaction_type_id,
            'desc' => ' دفع لقيمة الفاتورة '.$invoice->id,
            'branch_id' => $invoice->branch_id,
            'user_id' => Auth::id(),
         ]); 

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
