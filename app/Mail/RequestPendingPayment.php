<?php

// app/Mail/RequestPendingPayment.php
namespace App\Mail;

use App\Models\DoctorMail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RequestPendingPayment extends Mailable
{
    use Queueable, SerializesModels;

    public DoctorMail $mail;

    public function __construct(DoctorMail $mail)
    {
        $this->mail = $mail;
    }

    public function build()
    {
        return $this->subject('طلبك قيد الدفع')
                    ->view('emails.pending_payment', [
                        'mail' => $this->mail,
                    ]);
    }
}
