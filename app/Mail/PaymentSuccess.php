<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\DoctorMail;

class PaymentSuccess extends Mailable
{
    use Queueable, SerializesModels;

    public DoctorMail $mail;

    public function __construct(DoctorMail $mail)
    {
        $this->mail = $mail;
    }

    public function build()
    {
        return $this->subject('تم سداد طلبك بنجاح')
                    ->view('emails.payment_success', [
                        'mail' => $this->mail,
                    ]);
    }
}
