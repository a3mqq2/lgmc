<?php

namespace App\Mail;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\MedicalFacility;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class FacilityApproved extends Mailable
{
    use Queueable, SerializesModels;


    public MedicalFacility $facility;
    public Invoice $invoice;
    

public function __construct(MedicalFacility $facility, Invoice $invoice)
{
    $this->facility = $facility;
    $this->invoice  = $invoice;
}

public function build()
{
    return $this->subject('تم تفعيل منشأتك الطبية')
                ->markdown('emails.facilities.approved', [
                    'facility' => $this->facility,
                    'invoice'  => $this->invoice,
                ]);
}

}
